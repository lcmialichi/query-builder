<?php

namespace QueryBuilder;

use QueryBuilder\Contracts\Macro;
use QueryBuilder\Contracts\Connection;
use QueryBuilder\Exception\MacroException;
use QueryBuilder\Exception\ConnectionException;
use QueryBuilder\Macro\Bags\ParameterBag;

class Orchestrator
{
    public function __construct(private Connection $connection)
    {
        $this->buildConnectionIfNotStablished();
    }

    public function __call(string $method, array $arguments): mixed
    {
        return $this->runMacro($method, $arguments);
    }

    /** @throws MacroException */
    private function runMacro(string $name, mixed $params): Macro
    {
        if ($this->macroExists($name)) {
            return $this->getMacroInstance($name, $params);
        }

        throw MacroException::macroNotFound($name);
    }

    private function macroExists(string $macro): bool
    {
        return class_exists($this->getMacroNamespace($macro));
    }

    private function getMacroInstance(string $name, mixed $params): Macro
    {
        $macro = $this->getMacroNamespace($name);
        $macro = new $macro($this, ...$params);
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

    public function getConnection(): Connection
    {
        return $this->connection;
    }
}
