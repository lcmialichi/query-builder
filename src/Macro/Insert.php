<?php


namespace QueryBuilder\Macro;

use QueryBuilder\QueryBuilder;
use QueryBuilder\Contracts\Macro;
use QueryBuilder\Macro\Statement;
use QueryBuilder\Macro\Statements\IntoStatement;

class Insert extends Statement implements Macro
{
    use IntoStatement;

    public function __construct(private QueryBuilder $queryBuilder, ?Statement $previous = null, private array $params = [])
    {
        parent::__construct($queryBuilder, $previous);
        $this->setStatementOption(":fields", array_keys($params));
        $this->setStatementOption(":values", array_values($params));
    }

}
