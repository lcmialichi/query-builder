<?php

declare(strict_types=1);

namespace QueryBuilder\Macro\Expressions;

use QueryBuilder\Macro\Bags\ParameterBag;
use QueryBuilder\Exception\ExpressionException;

abstract class ExpressionOrchestrator
{
    private array $expressions = [];

    protected ?string $alias = null;

    private array $basicStatement = [
        "statement" => "(%s) :alias",
    ];

    public function __construct(
        protected ?string $field = null,
        private ParameterBag $parameterBag = new ParameterBag()
    ) {
        $this->setSeparetor(",");
    }

    public function withAlias(string $alias): self
    {
        $this->alias = $alias;
        return $this;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function resolve(): string
    {
        $this->getParameterBag()->add([":alias" => $this->getAlias()]);
        return sprintf($this->basicStatement['statement'], implode(" ", $this->expressions));
    }

    public function setSeparetor(string $separetor): self
    {
        $this->getParameterBag()->add([":separator" => $separetor]);
        return $this;
    }

    protected function addParameters(array $parameters): void
    {
        $this->getParameterBag()->add($parameters);
    }

    public function addParameterTo(string $notation, array $parameters): void
    {
        $this->getParameterBag()->addParameterTo($notation, $parameters);
    }

    protected function hasExpression(): bool
    {
        return !empty($this->expressions);
    }

    protected function getLastExpression(): false|string
    {
        return end($this->expressions);
    }

    public function addExpression(string $context, array $arguments = []): void
    {
        $statement = $this->getExpressionStatement($context);
        if ($this->getLastExpression() && !$this->lastExpressionIn("AND", "OR")) {
            if ($statement !== "OR" && $statement !== "AND") {
                $this->expressions[] = ":separator";
            }
        }
        $arguments = $this->buildArgs($arguments);
        $this->expressions[] = $this->replaceOnStatement($statement, $arguments);
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

    private function getParameterBag(): ParameterBag
    {
        return $this->parameterBag;
    }

    public function getParametersAsArray(): array
    {
        return $this->getParameterBag()->getParameters();
    }

    public function __toString(): string
    {
        return serialize($this);
    }

    public function __serialize(): array
    {
        return [
            "parameterBag" => $this->parameterBag,
            "basicStatement" => $this->basicStatement,
            "alias" => $this->alias,
            "expressions" => $this->expressions,
        ];
    }

}
