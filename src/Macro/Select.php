<?php

namespace QueryBuilder\Macro;

use QueryBuilder\QueryBuilder;
use QueryBuilder\Contracts\Macro;
use QueryBuilder\Macro\Statement;
use QueryBuilder\Contracts\Expression;
use QueryBuilder\Macro\Statements\FromStatement;
use QueryBuilder\Macro\Statements\JoinStatement;
use QueryBuilder\Macro\Statements\WhereStatement;
use QueryBuilder\Macro\Statements\GroupByStatement;
use QueryBuilder\Macro\Statements\OrderByStatement;

class Select extends Statement implements Macro
{
    use FromStatement,
        WhereStatement,
        JoinStatement,
        GroupByStatement,
        OrderByStatement;

    public function __construct(private QueryBuilder $queryBuilder, mixed $params = null)
    {
        parent::__construct($queryBuilder);
        $this->fields($params ?? ["*"]);
    }

    public function fields(mixed $params): self
    {
        $this->removeStatementOption(":fields");
        $this->addFields($params);
        return $this;
    }

    public function addField(string $field, ?string $alias = null): self
    {
        $this->addStatementOption(":fields", [
            "statement" => ":field" . (!$alias ? "" : " AS :alias"),
            ":field" => $field,
            ":alias" => $alias
        ]);

        return $this;
    }

    public function addFields(mixed $params): self
    {
        if (!is_array($params)) {
            $params = [$params];
        }

        $withAlias = !array_is_list($params);
        foreach ($params as $key => $value) {
            $field = $key;
            $alias = $value;

            if (!$withAlias) {
                $field = $value;
                $alias = null;
            }

            if ($this->isExpression($field)) {
                $this->addFieldExpression($field, $alias);
                continue;
            }

            $this->addField($field, $alias);

        }
        return $this;
    }

    private function addFieldExpression(Expression $expression, ?string $alias)
    {
        $this->addStatementOption(":fields", [
            "statement" => "( :expression ) " . (!$alias ? "" : " AS :alias"),
            ":alias" => $alias,
            ":expression" => [[
                "statement" => $expression->resolve(),
                ...$expression->getParameters()
            ]],
        ]);
    }

}
