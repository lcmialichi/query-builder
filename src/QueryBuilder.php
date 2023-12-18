<?php

namespace QueryBuilder;

use QueryBuilder\Macro\Expressions\Expression;
use QueryBuilder\Macro\Bags\ParameterBag;

/**
 * @method \QueryBuilder\Macro\Select select(mixed $columns = null)
 * @method \QueryBuilder\Macro\Insert insert(array $values)
 * @method \QueryBuilder\Macro\Update update(string $table)
 * @method \QueryBuilder\Macro\Delete delete()
 */
class QueryBuilder extends Orchestrator
{
    public function withRollBack(): self
    {
        $this->getConnection()->disableAutoCommit();
        return $this;
    }

    public static function expr(?string $col = null): Expression
    {
        return new Expression($col, new ParameterBag());
    }
}
