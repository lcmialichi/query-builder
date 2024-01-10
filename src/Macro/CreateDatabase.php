<?php

declare(strict_types=1);

namespace QueryBuilder\Macro;

use QueryBuilder\QueryBuilder;
use QueryBuilder\Contracts\Macro;

class CreateDatabase extends Statement implements Macro
{
    public function __construct(QueryBuilder $queryBuilder, string $table)
    {
        parent::__construct($queryBuilder);
        $this->setStatementOption(":database.name", $table);
    }
}
