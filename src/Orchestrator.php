<?php

namespace QueryBuilder;

use QueryBuilder\Contracts\Macro;
use QueryBuilder\Contracts\Connection;
use QueryBuilder\Exception\ConnectionException;
use QueryBuilder\Exception\QueryBuilderException;

class Orchestrator
{
    public function __construct(private Connection $connection)
    {
    }

    public function __call(string $method, array $arguments): mixed
    {
        $this->buildConnectionIfNotStablished();
        return $this->getMacroStatement($method, $arguments);
    }

    /** @throws QueryBuilderException */
    private function getMacroStatement(string $name, mixed $params): Macro
    {
        if ($this->macroStatementExists($name)) {
            return $this->instantiateMacroStatement($name, $params);
        }

        throw QueryBuilderException::macroNotFound($name);
    }

    private function macroStatementExists(string $macro): bool
    {
        return class_exists($this->getMacroNamespace($macro));
    }

    private function instantiateMacroStatement(string $name, mixed $params): Macro
    {
        $macro = $this->getMacroNamespace($name);
        return new $macro($this, ...$params);
    }

    private function getMacroNamespace(string $name): string
    {
        return configs("paths.macro") . "\\{$this->getStringPattern($name)}";
    }

    private function getStringPattern(string $string): string
    {
        return ucfirst(strtolower($string));
    }

    /** @throws ConnectionException */
    private function buildConnectionIfNotStablished(): void
    {
        try {
            if (!$this->getConnection()->hasConnection()) {
                $this->getConnection()->createConnection();
            }
        } catch (\Throwable $e) {
            throw ConnectionException::unableToEstablishConnection($e->getMessage());
        }
    }

    public function getConnection(): Connection
    {
        return $this->connection;
    }
}
