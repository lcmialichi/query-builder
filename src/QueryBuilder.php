<?php

namespace QueryBuilder;

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
        $this->connection->disableAutoCommit();
        return $this;
    }
}
