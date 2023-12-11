<?php

namespace QueryBuilder\Macro\Statements;

trait WhereStatement
{
    public function where(string|callable $column, ?string $operator = null, mixed $value = null): self
    {
        if (is_callable($column)) {
            return $this->withExpression(":where", $column);
        }

        $this->addStatementOption(':where', [
            "statement" => ":whereType ( :column :operator :value )",
            ":operator" => $operator,
            ":column" => $column,
            ":value" => $value,
            ":whereType" => $this->typeWhereIfNotExists("and")
        ]);
        return $this;
    }

    public function orWhere(string $column, string $operator, mixed $value): self
    {
        if (is_callable($column)) {
            return $this->withExpression(":where", $column);
        }

        $this->addStatementOption(':where', [
            "statement" => ":whereType ( :column :operator :value )",
            ":operator" => $operator,
            ":column" => $column,
            ":value" => $value,
            ":whereType" => $this->typeWhereIfNotExists("or")
        ]);
        return $this;
    }

    private function typeWhereIfNotExists(string $type): ?string
    {
        return $this->exists(":where") === false ? null : $type;
    }

}
