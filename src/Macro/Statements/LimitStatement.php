<?php

declare(strict_types=1);

namespace QueryBuilder\Macro\Statements;

trait LimitStatement
{
    public function limit(int $limit): self
    {
        $this->setStatementOption(":limit", $limit);
        return $this;
    }

    public function offset(int $offset): self
    {
        $this->setStatementOption(":offset", $offset);
        return $this;
    }
}
