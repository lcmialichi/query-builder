<?php

declare(strict_types=1);

namespace QueryBuilder\Macro;

use QueryBuilder\QueryBuilder;
use QueryBuilder\Contracts\Macro;
use QueryBuilder\Macro\Statements\SetStatement;
use QueryBuilder\Macro\Statements\WhereStatement;

class Update extends Statement implements Macro
{
    use WhereStatement,
        SetStatement;

    public function __construct(QueryBuilder $queryBuilder, string $table)
    {
        parent::__construct($queryBuilder);
        $this->setStatementOption(":table.name", $table);
    }
}
