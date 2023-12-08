<?php

namespace QueryBuilder\Macro\Builder;

use QueryBuilder\Contracts\Macro;

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

    public function __construct(private Macro $macro, private array $paramemters = [])
    {
    }

    public function getStructure(): string
    {
        $structure = $this->getMacro();
        if ($structure !== false) {
            return $structure ;
        }

        throw new \Exception(sprintf('unable to find query structure in:', $this->macro::class));
    }

    public function getParameters(): array
    {
        return $this->paramemters;
    }

    public function has(string $statement): bool
    {
        return isset($this->paramemters[$statement]);
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
        $structure =  ":" . strtolower(end($structure));
        return self::STATEMENT_LIST["macro"][$structure] ?? false;

    }

    public function getDelimiter(string $field): string
    {
        return match ($field) {
            ":fields" => ",",
            ":values" => ", ",
            ":group" => ", ",
            default => " "
        };
    }

}
