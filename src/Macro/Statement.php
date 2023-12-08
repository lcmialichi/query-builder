<?php

namespace QueryBuilder\Macro;

use QueryBuilder\QueryBuilder;
use QueryBuilder\Macro\Builder\Builder;
use QueryBuilder\Macro\Builder\BaseStructure;
use QueryBuilder\Macro\Statements\WhereStatement;

abstract class Statement
{
    protected array $statement = [];

    private array $params = [];

    public function __construct(private QueryBuilder $queryBuilder)
    {
    }

    public function getStatementOptions(): array
    {
        return $this->statement;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function addParams(array $params): void
    {
        foreach ($params as $param => $value) {
            $this->addParam($param, $value);
        }
    }

    public function addParam(string $param, mixed $value): void
    {
        $this->params[ltrim(":", $param)] = $value;
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


    public function execute()
    {
        $this->queryBuilder->getConnection()->execute(dd($this->buildQuery()));
    }


}
