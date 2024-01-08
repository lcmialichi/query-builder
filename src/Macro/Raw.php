<?php

declare(strict_types=1);

namespace QueryBuilder\Macro;

use QueryBuilder\QueryBuilder;
use QueryBuilder\Contracts\Macro;

class Raw extends Statement implements Macro
{
    public function __construct(QueryBuilder $queryBuilder, string $raw)
    {
        parent::__construct($queryBuilder);
        $this->addStatementOption(':raw', $raw);
    }
}
