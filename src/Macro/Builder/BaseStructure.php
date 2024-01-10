<?php

namespace QueryBuilder\Macro\Builder;

use QueryBuilder\Enum\Verbosity;
use QueryBuilder\Contracts\Macro;
use QueryBuilder\Macro\Bags\ParameterBag;

class BaseStructure
{
    private const STATEMENT_LIST = [
        "macro" => [
            ':select' => 'SELECT :fields FROM :table.name :table.alias',
            ':insert' => 'INSERT INTO :table.name :table.alias ( :fields ) VALUES ( :values )',
            ':update' => 'UPDATE :table.name :table.alias SET :sets',
            ':delete' => 'DELETE FROM :table.name :table.alias',
            ':raw' => ':raw',
            ':createtable' => "CREATE TABLE :table.name ( :columns )",
            ':database' => "CREATE DATABASE :database.name",
        ],
        "micro" => [
            ':where' => ' WHERE :where',
            ':join' => ' :join',
            ':group' => ' GROUP BY :group',
            ':order' => ' :order',
            ':limit' => ' LIMIT :limit',
            ':offset' => ' OFFSET :offset',
        ]
    ];

    private const INPUT_ASSIGNMENT = [
        // ":table.name" => "`%s`",
        // ":field" => "`%s`",
    ];

    public function __construct(
        private Macro $macro,
        private ParameterBag $queryParameters,
        private ParameterBag $valueParameters,
        private Verbosity $verbosity = Verbosity::VERBOSE
    ) {
    }

    public function getStructure(): string
    {
        $structure = $this->getMacro();
        if ($structure !== false) {
            return $structure;
        }
        throw new \Exception(sprintf('unable to find query structure in:', $this->macro::class));
    }

    public function getQueryParameters(): array
    {
        return $this->queryParameters->getParameters();
    }

    public function hasQueryParameter(string $parameter): bool
    {
        return $this->queryParameters->has($parameter);
    }

    public function hasValueParameter(?string $parameter): bool
    {
        return $this->valueParameters->has($parameter);
    }

    public function getValueParameter(string $parameter): mixed
    {
        return $this->valueParameters->get($parameter);
    }

    public function has(string $statement): bool
    {
        return $this->queryParameters->has($statement);
    }

    public function get(string $statement): string
    {
        if (!$this->has($statement)) {
            return "";
        }

        return self::STATEMENT_LIST["micro"][$statement];
    }

    private function getMacro(): string|bool
    {
        $structure = explode("\\", $this->macro::class);
        $structure = ":" . strtolower(end($structure));
        return self::STATEMENT_LIST["macro"][$structure] ?? false;
    }

    public function getVerbosity(): Verbosity
    {
        return $this->verbosity;
    }

    public function prepareValue(string $context, mixed $value): mixed
    {
        $context = $this->prepareContext($context);
        if (is_array($value)) {
            return implode(
                $this->getDelimiter($context),
                array_map(fn($item) => $this->prepareValue($context, $item), $value)
            );
        }

        if ($this->getVerbosity()->isVerbose()) {
            if ($this->hasInputAssignment($context) && !$this->hasValueParameter($value)) {
                if ($this->needInputAssignmentFormat($value)) {
                    $value = sprintf($this->getInputAssignment($context), $value);
                }
            }
        }
        return $value;
    }

    private function needInputAssignmentFormat(mixed $value): ?bool
    {
        return match (gettype($value)) {
            "boolean" => false,
            "integer" => false,
            "double" => false,
            "string" => true,
            "array" => true,
            default => false
        };
    }

    private function getDelimiter(string $field): string
    {
        return match ($field) {
            ":fields" => ", ",
            ":sets" => ", ",
            ":values" => ", ",
            ":value" => ", ",
            ":group" => ", ",
            ":columns" => ",\n",
            default => " "
        };
    }

    private function prepareContext(string $context): string
    {
        return $context = explode("_", $context)[0];
    }

    private function hasInputAssignment(string $context): bool
    {
        return isset(self::INPUT_ASSIGNMENT[$context]);
    }

    private function getInputAssignment(string $context): ?string
    {
        return self::INPUT_ASSIGNMENT[$context] ?? null;
    }

}
