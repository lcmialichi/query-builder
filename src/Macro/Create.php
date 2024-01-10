<?php

namespace QueryBuilder\Macro;

use QueryBuilder\QueryBuilder;
use QueryBuilder\Contracts\Macro;
use QueryBuilder\Macro\CreateDatabase;

class Create implements Macro
{
    public function __construct(private QueryBuilder $queryBuilder)
    {
    }

    public function table(string $table): CreateTable
    {
        return new CreateTable($this->queryBuilder, $table);
    }

    public function database(string $name)
    {
        return new CreateDatabase($this->queryBuilder, $name);
    }

}
