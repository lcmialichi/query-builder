<?php

namespace QueryBuilder\Macro;

use QueryBuilder\QueryBuilder;
use QueryBuilder\Macro\Builder\Builder;
use QueryBuilder\Connection\QueryResult;
use QueryBuilder\Macro\Builder\BaseStructure;

abstract class Statement
{
    protected array $statement = [];

    private array $params = [];

    public function __construct(private QueryBuilder $queryBuilder)
    {
    }

    /** @return array<mixed> */
    public function getStatementOptions(): array
    {
        return $this->statement;
    }

    /** @return array<mixed> */
    public function getParams(): array
    {
        return $this->params;
    }

    public function addParams(array $params): self
    {
        foreach ($params as $param => $value) {
            $this->addParam($param, $value);
        }
        return $this;
    }

    public function addParam(string $param, mixed $value): self
    {
        $this->params[$param] = $value;
        return $this;
    }

    protected function addStatementOption(string $option, mixed $value): void
    {
        $this->statement[$option][] = $value;
    }

    public function setStatementOption(string $option, mixed $value): void
    {
        $this->statement[$option] = $value;
    }

    protected function removeStatementOption(string $option): void
    {
        unset($this->statement[$option]);
    }

    private function getBuilder(array $params): Builder
    {
        return new Builder(new BaseStructure($this, $params));
    }

    public function buildQuery(): string
    {
        $builder = $this->getBuilder($this->getStatementOptions());
        return $builder->build()->getQuery();
    }

    public function expression(): Expression
    {
        return new Expression;
    }

    /** @return array<mixed> */
    protected function withExpression(string $context, callable $callable): self
    {
        $expr = $callable($this->expression());
        $this->addStatementOption($context, [
            "statement" => sprintf(" ( %s )", $expr->getExpression()),
            ...$expr->getParameters()
        ]);

        return $this;
    }

    protected function exists(string $context): bool
    {
        return !empty($this->statement[$context]);
    }

    public function execute(): QueryResult
    {
        return (new QueryResult(
            $this->queryBuilder->getConnection(),
            $this->buildQuery(),
            $this->getParams()
        ))->execute();
    }


}
