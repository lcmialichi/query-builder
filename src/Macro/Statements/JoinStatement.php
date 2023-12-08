<?php

declare(strict_types=1);

namespace QueryBuilder\Macro\Statements;

trait JoinStatement
{
    public function join(string $table, string $alias, string $on): self
    {
        $this->addStatementOption(':join', [
            "statement" => ":type JOIN :table ON :on",
            ":type" => "INNER",
            ":table" => $table,
            ":alias" => $alias,
            ":on" => $on
        ]);
        return $this;
    }


    public function leftJoin(string $table, string $alias, string $on): self
    {
        $this->addStatementOption(':join', [
            "statement" => ":type JOIN :table ON :on",
            ":type" => "LEFT",
            ":table" => $table,
            ":alias" => $alias,
            ":on" => $on
        ]);
        return $this;
    }


    public function rightJoin(string $table, string $alias, string $on): self
    {
        $this->addStatementOption(':join', [
            "statement" => ":type JOIN :table ON :on",
            ":type" => "RIGHT",
            ":table" => $table,
            ":alias" => $alias,
            ":on" => $on
        ]);
        return $this;
    }
}
