<?php

namespace QueryBuilder\Macro\Schemma;

class Column
{
    private string $statement = ':name :type :default :notNull :autoIncrement :unique :check :primaryKey :comment';
    private array $options = [];

    public function __construct(string $name)
    {
        $this->options[":name"] = $name;
    }

    public function notNull(): self
    {
        $this->options[":notNull"] = "NOT NULL";
        return $this;
    }

    public function primaryKey(): self
    {
        $this->options[":primaryKey"] = "PRIMARY KEY";
        return $this;
    }

    public function check(string $condition): self
    {
        $this->options[":check"] = "CHECK ($condition)";
        return $this;
    }

    public function unique(): self
    {
        $this->options[":unique"] = "UNIQUE";
        return $this;
    }

    public function autoIncrement(): self
    {
        $this->options[":autoIncrement"] = "AUTO_INCREMENT";
        return $this;
    }

    public function default(mixed $value): self
    {
        $this->options[":default"] = $value;
        return $this;
    }

    public function int(int $size): self
    {
        $this->options[":type"] = "INT($size)";
        return $this;
    }

    public function varchar(int $size): self
    {
        $this->options[":type"] = "VARCHAR($size)";
        return $this;
    }

    public function bool(): self
    {
        $this->options[":type"] = "BOOL";
        return $this;
    }

    public function text(): self
    {
        $this->options[":type"] = "TEXT";
        return $this;
    }

    public function date(): self
    {
        $this->options[":type"] = "DATE";
        return $this;
    }

    public function datetime(): self
    {
        $this->options[":type"] = "DATETIME";
        return $this;
    }

    public function timestamp(): self
    {
        $this->options[":type"] = "TIMESTAMP";
        return $this;
    }

    public function time(): self
    {
        $this->options[":type"] = "TIME";
        return $this;
    }

    public function year(): self
    {
        $this->options[":type"] = "YEAR";
        return $this;
    }

    public function float(): self
    {
        $this->options[":type"] = "FLOAT";
        return $this;
    }

    public function double(): self
    {
        $this->options[":type"] = "DOUBLE";
        return $this;
    }

    public function decimal(): self
    {
        $this->options[":type"] = "DECIMAL";
        return $this;
    }

    public function binary(): self
    {
        $this->options[":type"] = "BINARY";
        return $this;
    }

    public function varbinary(): self
    {
        $this->options[":type"] = "VARBINARY";
        return $this;
    }

    public function tinyblob(): self
    {
        $this->options[":type"] = "TINYBLOB";
        return $this;
    }

    public function blob(): self
    {
        $this->options[":type"] = "BLOB";
        return $this;
    }

    public function mediumblob(): self
    {
        $this->options[":type"] = "MEDIUMBLOB";
        return $this;
    }

    public function longblob(): self
    {
        $this->options[":type"] = "LONGBLOB";
        return $this;
    }

    public function tinytext(): self
    {
        $this->options[":type"] = "TINYTEXT";
        return $this;
    }

    public function mediumtext(): self
    {
        $this->options[":type"] = "MEDIUMTEXT";
        return $this;
    }

    public function longtext(): self
    {
        $this->options[":type"] = "LONGTEXT";
        return $this;
    }

    public function enum(): self
    {
        $this->options[":type"] = "ENUM";
        return $this;
    }

    public function set(): self
    {
        $this->options[":type"] = "SET";
        return $this;
    }

    public function json(): self
    {
        $this->options[":type"] = "JSON";
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
