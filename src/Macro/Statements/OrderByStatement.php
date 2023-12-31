<?php

namespace QueryBuilder\Macro\Statements;

trait OrderByStatement
{
    public function orderBy(string $column, string $type = "ASC"): self
    {
        $this->setStatementOptions(":order", [
            "statement" => ":column :type",
            ":column" => $column,
            ":type" => $type
        ]);
        return $this;
    }
}
