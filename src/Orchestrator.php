<?php

namespace QueryBuilder;

use QueryBuilder\Contracts\Macro;
use QueryBuilder\Contracts\Connection;

/**
 * @method \QueryBuilder\Macro\Statement select(?array $columns = null)
 * @method \QueryBuilder\Macro\Statement insert(array $values)
 * @method \QueryBuilder\Macro\Statement update(string $table)
 * @method \QueryBuilder\Macro\Statement delete()
 */
class Orchestrator
{

    public function __construct(private Connection $connection)
    {
        if (!$connection->hasConnection()) {
            $connection->createConnection();
        }
    }

    public function __call(string $method, array $arguments): mixed
    {
        return $this->getMacroStatement($method, $arguments);
    }

    private function getMacroStatement(string $name, mixed $params): Macro
    {
        $name = ucfirst(strtolower($name));
        if ($this->macroStatementExists($name)) {
            return $this->instantiateMacroStatement($name, $params);
        }

        throw new \Exception("Macro `{$name}` does not exist");
    }

    private function macroStatementExists(string $macro): bool
    {
        return class_exists(configs("paths.macro") . "\\{$macro}");
    }

    private function instantiateMacroStatement(string $name, mixed $params): Macro
    {
        $macro = configs("paths.macro");
        $macro = "{$macro}\\{$name}";
        return new $macro($this, ...$params);
    }

}
