<?php

namespace QueryBuilder\Macro\Statements;

trait WhereStatement
{
    public function where(string $column, string $operator, mixed $value): self
    {
        $type = empty($this->statement[':where']) ? null : "and";
        $this->statement[':where'][] = [
            "statement" => "{$type} ( :column :operator :value )",
            ":operator" => $operator,
            ":column" => $column,
            ":value" => $value
        ];
        
        $this->addParam(":{$column}", $value);
        return $this;
    }

    public function orWhere(string $column, string $operator, mixed $value): self
    {
        $type = empty($this->statement[':where']) ? null : "or";
        $this->statement[':where'][] = [
            "statement" => "{$type} ( :column :operator :value )",
            ":operator" => $operator,
            ":column" => $column,
            ":value" => $value
        ];

        $this->addParam(":{$column}", $value);
        return $this;
    }

}
