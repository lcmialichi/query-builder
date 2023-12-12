<?php

namespace QueryBuilder\Macro\Builder;

use QueryBuilder\Contracts\Macro;
use QueryBuilder\Enum\Verbosity;

class BaseStructure
{
    private const STATEMENT_LIST = [
        "macro" => [
            ':select' => 'SELECT :fields FROM :table.name :table.alias',
            ':insert' => 'INSERT INTO :table.name :table.alias ( :fields ) VALUES ( :values )',
            ':update' => 'UPDATE :table.name :table.alias SET :fields',
            ':delete' => 'DELETE FROM :table.name :table.alias'
        ],
        "micro" => [
            ':where' => ' WHERE :where',
            ':join' => ' :join',
            ':group' => ' GROUP BY :group',
            ':order' => ' :order',
        ]
    ];

    private const INPUT_ASSIGNMENT = [
        ":values" => "'%s'",
        ":alias" => "`%s`",
    ];

    public function __construct(
        private Macro $macro,
        private array $queryParameters = [],
        private array $valueParameters = [],
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
        return $this->queryParameters;
    }

    public function hasQueryParameter(string $parameter): bool
    {
        return isset($this->queryParameters[$parameter]);
    }

    public function hasValueParameter(string $parameter): bool
    {
        return isset($this->valueParameters[$parameter]);
    }

    public function getValueParameter(string $parameter): mixed
    {
        return $this->valueParameters[$parameter];
    }

    public function has(string $statement): bool
    {
        return isset($this->queryParameters[$statement]);
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
            ":fields" => ",",
            ":values" => ", ",
            ":value" => ", ",
            ":group" => ", ",
            default => " "
        };
    }

    private function prepareContext(string $context): string
    {
        return $context = explode("_", $context)[0];
    }

    private function hasInputAssignment(string $context): bool
    {
        return isset(self::INPUT_ASSIGNMENT[explode("_", $context)[0]]);
    }

    private function getInputAssignment(string $context): ?string
    {
        return self::INPUT_ASSIGNMENT[explode("_", $context)[0]] ?? null;
    }

}
