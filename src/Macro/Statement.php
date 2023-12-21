<?php

namespace QueryBuilder\Macro;

use QueryBuilder\Macro\Bags\ParameterBag;
use QueryBuilder\QueryBuilder;
use QueryBuilder\Macro\Expressions\Expression;
use QueryBuilder\Macro\Builder\Builder;
use QueryBuilder\Connection\QueryResult;
use QueryBuilder\Macro\Builder\BaseStructure;

class Statement
{
    public function __construct(
        private QueryBuilder $queryBuilder,
        private ParameterBag $statementParametersBag = new ParameterBag(),
        private ParameterBag $queryParameterBag = new ParameterBag()
    ) {
    }

    /** @return $this */
    public function addParams(array $params): self
    {
        $this->getQueryParameterBag()->addParameters($params);
        return $this;
    }

    /** @return $this */
    public function addParam(string $param, mixed $value): self
    {
        $this->getQueryParameterBag()->setParameter($param, $value);
        return $this;
    }

    protected function addStatementOption(string $option, mixed $value): void
    {
        $this->statementParametersBag()->addIntoParameter($option, $value);
    }

    public function setStatementOption(string $option, mixed $value): void
    {
        $this->statementParametersBag()->setParameter($option, $value);
    }

    protected function removeStatementOption(string $option): void
    {
        $this->statementParametersBag()->remove($option);
    }

    private function getBuilder(ParameterBag $statementParametersBag): Builder
    {
        return new Builder(new BaseStructure($this, $statementParametersBag, $this->getQueryParameterBag()));
    }

    public function buildQuery(): string
    {
        $builder = $this->getBuilder($this->statementParametersBag());
        return $builder->build()->getQuery();
    }

    public function expr(?string $column = null): Expression
    {
        return new Expression($column);
    }

    protected function addExpressionToStatement(
        string $target,
        string $defaultStatement,
        Expression $expression,
        array $params = []
    ) {
        $this->addStatementOption($target, [
            "statement" => $defaultStatement,
            ":expression" => serialize($expression->setSeparetor("and"))
        ]);
    }

    protected function isExpression(mixed $context): bool
    {
        return $context instanceof Expression;
    }

    protected function exists(string $context): bool
    {
        return $this->statementParametersBag->has($context);
    }

    public function toSql(): string
    {
        return $this->buildQuery();
    }

    private function statementParametersBag(): ParameterBag
    {
        return $this->statementParametersBag;
    }

    private function getQueryParameterBag(): ParameterBag
    {
        return $this->queryParameterBag;
    }

    public function execute(): QueryResult
    {
        return (new QueryResult(
            $this->queryBuilder->getConnection(),
            $this->buildQuery(),
            $this->getQueryParameterBag()->getParameters()
        ))->execute();
    }


}
