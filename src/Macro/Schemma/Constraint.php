<?php

declare(strict_types=1);

namespace QueryBuilder\Macro\Schemma;

class Constraint
{
    private string $statement = "CONSTRAINT :name FOREIGN KEY ( :foreignKey ) :references";
    private array $options = [];

    public function __construct(string $name)
    {
        $this->options[":name"] = $name;
    }

    public function fk(string $column): self
    {
        $this->options[":foreignKey"] = $column;
        return $this;
    }

    public function references(string $table, string $column): self
    {
        $this->options[":references"][] = [
            "statement" => "REFERENCES :table ( :referenceColumn )",
            ":table" => $table,
            ":referenceColumn" => $column
        ];

        return $this;
    }

    public function toArray(): array
    {
        return [
            "statement" => $this->statement,
            ...$this->options
        ];
    }
}
