<?php

declare(strict_types=1);

namespace QueryBuilder\Macro\Expressions;

abstract class ExpressionOrchestrator
{
    protected array $expressionUsage = [];

    protected array $parameter = [];

    public function resolve(): string
    {
        return implode(" ", $this->expressionUsage);
    }

    public function getParameters(): array
    {
        return $this->parameter;
    }

    protected function addParameters(array $parameters): void
    {
        $this->parameter = array_merge($this->parameter, $parameters);
    }

    public function addParameterTo(string $notation, array $parameters): void
    {
        $parameters = array_merge(dot($notation, $this->parameter) ?? [], $parameters);
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

    protected function getLastExpression(): string
    {
        return end($this->expressionUsage);
    }

    protected function addExpression(string $statement, array $arguments = []): void
    {
        $this->expressionUsage[] = $this->replaceOnStatement($statement, $arguments);
        $this->addParameters($arguments);
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

    protected function expressionExists(string $name): bool
    {
        return isset($this->getExprList()[$name]);
    }


    public abstract function getExprList(): array;

}
