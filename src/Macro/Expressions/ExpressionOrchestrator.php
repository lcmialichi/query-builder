<?php

declare(strict_types=1);

namespace QueryBuilder\Macro\Expressions;

use QueryBuilder\Macro\Statement;
use QueryBuilder\QueryBuilder;

abstract class ExpressionOrchestrator
{
    public function __construct(private Statement &$statement)
    {
    }

    protected array $expressionUsage = [];

    protected array $parameter = [];

    protected function getStatement(): Statement
    {
        return $this->statement;
    }

    public function resolve(): string
    {
        return implode(" ", $this->expressionUsage);
    }

    public function getParameters(): array
    {
        return $this->parameter;
    }

    public function setSeparetor(string $separetor): self
    {
        $this->addParameters([":separator" => $separetor]);
        return $this;
    }

    protected function addParameters(array $parameters): void
    {
        $this->parameter = array_merge($this->parameter, $parameters);
    }

    public function addParameterTo(string $notation, array $parameters): void
    {
        $notation = explode(".", $notation);
        if (!is_null($notation)) {
            $reference = &$this->parameter;
            $i = count($notation);
            foreach ($notation as $key) {
                if (!isset($reference[$key])) {
                    $reference[$key] = [];
                }
                $reference = &$reference[$key];
                if ($i-- == 1) {
                    $reference[] = $parameters;
                }
            }
        }
    }

    protected function hasExpression(): bool
    {
        return !empty($this->expressionUsage);
    }

    protected function getLastExpression(): false|string
    {
        return end($this->expressionUsage);
    }

    public function addExpression(string $statement, array $arguments = []): void
    {
        if ($this->getLastExpression() && !$this->lastExpressionIn("AND", "OR")) {
            if ($statement !== "OR" && $statement !== "AND") {
                $this->expressionUsage[] = ":separator";
            }
        }
        $arguments = $this->buildArgs($arguments);
        $this->expressionUsage[] = $this->replaceOnStatement($statement, $arguments);
        $this->addParameters($arguments);
    }

    protected function buildArgs(array $args): array
    {
        $items = [];
        foreach ($args as $key => $value) {
            $items[$key . "_" . uniqid()] = $value;
        }

        return $items;
    }

    protected function replaceOnStatement(string $statement, array $arguments = []): string
    {
        return sprintf($statement, ...array_keys($arguments));
    }

    protected function getExpressionStatement(string $name): string
    {
        if (!$this->expressionExists($name)) {
            throw new \Exception("Expression `{$name}` does not exist");
        }

        return $this->getExprList()[$name] ?? "";
    }

    protected function lastExpressionIn(string ...$expressions): bool
    {
        return in_array($this->getLastExpression(), $expressions);
    }

    protected function expressionExists(string $name): bool
    {
        return isset($this->getExprList()[$name]);
    }

    public abstract function getExprList(): array;

    public function __toString(): string
    {
        $this->getStatement()->addParams($this->getParameters());
        return $this->resolve();
    }

}
