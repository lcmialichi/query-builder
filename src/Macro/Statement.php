<?php

namespace QueryBuilder\Macro;

use QueryBuilder\Macro\Bags\ParameterBag;
use QueryBuilder\QueryBuilder;
use QueryBuilder\Macro\Expressions\Expression;
use QueryBuilder\Macro\Builder\Builder;
use QueryBuilder\Connection\QueryResult;
use QueryBuilder\Macro\Expressions\Expr;
use QueryBuilder\Macro\Builder\BaseStructure;

class Statement
{
    protected array $statement = [];

    private array $params = [];

    private ParameterBag $parameterBag;

    public function __construct(
        private QueryBuilder $queryBuilder,
        private ParameterBag $statementParameters
    ) {
        $this->parameterBag = new ParameterBag();
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

    /** @return $this */
    public function addParams(array $params): self
    {
        $this->getParameterBag()->addParameters($params);
        return $this;
    }

    /** @return $this */
    public function addParam(string $param, mixed $value): self
    {
        $this->getParameterBag()->setParameter($param, $value);
        return $this;
    }

    protected function addStatementOption(string $option, mixed $value): void
    {
        $this->statementParameters()->addIntoParameter($option, $value);
    }

    public function setStatementOption(string $option, mixed $value): void
    {
        $this->statementParameters()->setParameter($option, $value);
    }

    protected function removeStatementOption(string $option): void
    {
        $this->statementParameters()->remove($option);
    }

    private function getBuilder(ParameterBag $statementParameters): Builder
    {
        return new Builder(new BaseStructure($this, $statementParameters, $this->getParameterBag()));
    }

    public function buildQuery(): string
    {
        $builder = $this->getBuilder($this->statementParameters());
        return $builder->build()->getQuery();
    }

    public function expr(?string $column = null): Expression
    {
        return new Expression($column, $this->parameterBag);
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
        return !empty($this->statement[$context]);
    }

    public function toSql(): string
    {
        return $this->buildQuery();
    }

    private function statementParameters(): ParameterBag
    {
        return $this->statementParameters;
    }

    private function getParameterBag(): ParameterBag
    {
        return $this->parameterBag;
    }

    public function execute(): QueryResult
    {
        return (new QueryResult(
            $this->queryBuilder->getConnection(),
            $this->buildQuery(),
            $this->getParameterBag()->getParameters()
        ))->execute();
    }


}
