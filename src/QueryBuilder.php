<?php

namespace QueryBuilder;

use QueryBuilder\Macro\Statement;
use QueryBuilder\Contracts\Expression;
use QueryBuilder\Macro\Expressions\Expr;

/**
 * @method \QueryBuilder\Macro\Statement select(mixed $columns = null)
 * @method \QueryBuilder\Macro\Statement insert(array $values)
 * @method \QueryBuilder\Macro\Statement update(string $table)
 * @method \QueryBuilder\Macro\Statement delete()
 */
class QueryBuilder extends Orchestrator
{
    public function withRollBack(): self
    {
        $this->getConnection()->disableAutoCommit();
        return $this;
    }

    public function expr(?string $col = null, ?Statement $statemnt = null): Expression
    {
        $statement = $statemnt ?? $this->newStatement();
        return $this->previous = new Expr($col, $statement);
    }


}
