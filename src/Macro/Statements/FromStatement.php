<?php

declare(strict_types=1);

namespace QueryBuilder\Macro\Statements;

use QueryBuilder\Macro\Statement;

trait FromStatement
{
    public function from(string|Statement $table, ?string $alias = null): self
    {   
        if($table instanceof Statement) {
            $table = "({$table->toSql()})";
        }
        
        $this->setStatementOption(":table", [
            "statement" => ":table :alias",
            "name" => $table,
            "alias" => $alias
        ]);

        return $this;
    }
}
