<?php

namespace QueryBuilder\Macro\Statements;

use QueryBuilder\Macro\Expressions\Expression;

trait WhereStatement
{
    public function where(string|Expression $column, ?string $operator = null, mixed $value = null): self
    {
        if ($column instanceof Expression) {
            $this->addExpressionToStatement(":where", ":whereType ( :expression )", $column, [
                ":whereType" => $this->typeWhereIfNotExists("AND")
            ]);

            return $this;
        }

        $this->addStatementOption(':where', [
            "statement" => ":whereType ( :column :operator :value )",
            ":operator" => $operator,
            ":column" => $column,
            ":value" => $value,
            ":whereType" => $this->typeWhereIfNotExists("AND")
        ]);
        return $this;
    }

    public function orWhere(string|Expression $column, string $operator, mixed $value): self
    {
        if ($column instanceof Expression) {
            $this->addExpressionToStatement(":where", ":whereType ( :expression )", $column, [
                ":whereType" => $this->typeWhereIfNotExists("OR")
            ]);

            return $this;
        }

        $this->addStatementOption(':where', [
            "statement" => ":whereType ( :column :operator :value )",
            ":operator" => $operator,
            ":column" => $column,
            ":value" => $value,
            ":whereType" => $this->typeWhereIfNotExists("OR")
        ]);
        return $this;
    }

    private function typeWhereIfNotExists(string $type): ?string
    {
        return $this->exists(":where") === false ? null : $type;
    }

}
