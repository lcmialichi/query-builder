<?php

namespace QueryBuilder\Macro\Statements;

trait GroupByStatement
{
    public function groupBy(string $column): self
    {
        $this->addStatementOption(':group', [
            "statement" => ":column",
            ":column" => $column
        ]);
        return $this;
    }
}
