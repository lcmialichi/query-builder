<?php

declare(strict_types=1);

namespace QueryBuilder\Macro;

use QueryBuilder\QueryBuilder;
use QueryBuilder\Contracts\Macro;
use QueryBuilder\Macro\Schemma\Columns;

class Drop extends Statement implements Macro
{
    public function __construct(QueryBuilder $queryBuilder, ?string $table = null)
    {
        parent::__construct($queryBuilder);
        $this->setStatementOption(":table.name", $table);
    }

    public function ifExists(): self
    {
        $this->addStatementOption(":ifExists", "IF EXISTS");
        return $this;
    }

    public function table(string $table): self
    {
        $this->addStatementOption(":drop",$table);
        return $this;
    }
}
