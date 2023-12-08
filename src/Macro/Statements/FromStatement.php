<?php

declare(strict_types=1);

namespace QueryBuilder\Macro\Statements;

trait FromStatement
{
    public function from(string $table, ?string $alias = null): self
    {
        $this->setStatementOption(":table", [
            "statement" => ":table :alias",
            "name" => $table,
            "alias" => $alias
        ]);

        return $this;
    }
}
