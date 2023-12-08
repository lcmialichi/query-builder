<?php

namespace QueryBuilder\Macro;

use QueryBuilder\Macro\Statements\FromStatement;
use QueryBuilder\QueryBuilder;
use QueryBuilder\Contracts\Macro;
use QueryBuilder\Macro\Statement;
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

    public function __construct(private QueryBuilder $queryBuilder, ?array $params = null)
    {
        parent::__construct($queryBuilder);
        $this->fields($params ?? ["*"]);
    }

    public function fields(array|string $params): self
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

    public function addFields(array $params): self
    {
        foreach ($params as $key => $value) {
            if (!array_is_list($params)) {
                $this->addField($key, $value);
                continue;
            }
            $this->addField($value);

        }
        return $this;
    }

}
