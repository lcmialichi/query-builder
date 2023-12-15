<?php

namespace QueryBuilder;

use QueryBuilder\Contracts\Expression;
use QueryBuilder\Contracts\Macro;
use QueryBuilder\Contracts\Connection;
use QueryBuilder\Exception\MacroException;
use QueryBuilder\Exception\ConnectionException;
use QueryBuilder\Macro\Bags\ParameterBag;

class Orchestrator
{
    private ParameterBag $parameterBag;

    public function __construct(private Connection $connection)
    {
        $connection->disconnect();
        $this->buildConnectionIfNotStablished();
        $this->buildParameters();
    }

    public function __call(string $method, array $arguments): mixed
    {
        return $this->getMacroStatement($method, $arguments);
    }

    /** @throws MacroException */
    private function getMacroStatement(string $name, mixed $params): Macro
    {
        if ($this->macroStatementExists($name)) {
            return $this->instantiateMacroStatement($name, $params);
        }

        throw MacroException::macroNotFound($name);
    }

    private function macroStatementExists(string $macro): bool
    {
        return class_exists($this->getMacroNamespace($macro));
    }

    private function instantiateMacroStatement(string $name, mixed $params): Macro
    {
        $macro = $this->getMacroNamespace($name);
        $macro = new $macro($this, $this->getPrevious(), ...$params);
        if ($macro instanceof Macro) {
            return $macro;
        }

        throw MacroException::macroNotInstance(Macro::class);
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

    private function buildParameters(array $params = []): void
    {
        $this->parameterBag = new ParameterBag($params);
    }

    public function getConnection(): Connection
    {
        return $this->connection;
    }

}
