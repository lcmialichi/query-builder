<?php

declare(strict_types=1);

namespace QueryBuilder\Macro\Expressions;

use QueryBuilder\Macro\Bags\ParameterBag;
use QueryBuilder\Exception\ExpressionException;

abstract class ExpressionOrchestrator
{
    protected array $expressionUsage = [];

    protected array $parameter = [];

    public function __construct(private ?string $field = null, private ParameterBag $parameterBag)
    {
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

    protected function getCol(): string
    {
        if (isset($this->field)) {
            return $this->field;
        }

        throw ExpressionException::columnNotSet($this::class, __METHOD__);
    }

    public function col(string $column): self
    {
        $this->field = $column;
        return $this;
    }

    private function getParameterBag(): ParameterBag
    {
        return $this->parameterBag;
    }

}
