<?php

declare(strict_types=1);

namespace QueryBuilder\Macro;

use QueryBuilder\QueryBuilder;
use QueryBuilder\Contracts\Macro;
use QueryBuilder\Macro\Schemma\Columns;

class CreateTable extends Statement implements Macro
{
    public function __construct(QueryBuilder $queryBuilder, string $table)
    {
        parent::__construct($queryBuilder);
        $this->setStatementOption(":table.name", $table);
    }

    public function columns(callable $callable)
    {
        $table = $callable(new Columns);
        $columns = $table->getColumns() ?? [];
        $constraints = $table->getConstraints() ?? [];
        
        $this->addStatementOption(":columns",[
            "statement" => "\n:columns :separetor \n:constraints",
            ":separetor" => empty($constraints) ? null : ", ",
            ":columns" => $columns,
            ":constraints" => [$constraints]
        ]);

        return $this;
    }
}
