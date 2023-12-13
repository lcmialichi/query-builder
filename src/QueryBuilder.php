<?php

namespace QueryBuilder;

use QueryBuilder\Macro\Expression;


/**
 * @method \QueryBuilder\Macro\Statement select(?array $columns = null)
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

    public function expr(): Expression
    {
        return new Expression;
    }

}
