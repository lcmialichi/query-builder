<?php

namespace QueryBuilder\Macro\Statements;

trait IntoStatement
{
    public function into(string $table): self
    {
        $this->setStatementOption(":table", [
            "name" => $table,
        ]);

        return $this;
    }

}
