<?php

declare(strict_types=1);

namespace QueryBuilder\Macro\Expressions;

use QueryBuilder\Macro\Expressions\ExpressionOrchestrator;

class Expression extends ExpressionOrchestrator
{
    private array $expList = [
        ":and" => "AND",
        ":or" => "OR",
        ":where" => "%s %s %s",
        ":in" => "%s IN ( %s )",
        ":notIn" => "%s NOT IN ( %s )",
        ":between" => "%s BETWEEN %s AND %s",
        ":notBetween" => "%s NOT BETWEEN %s AND %s",
        ":like" => "%s LIKE %s",
        ":notLike" => "%s NOT LIKE %s",
        ":isNull" => "%s IS NULL",
        ":notNull" => "%s IS NOT NULL",
        ":caseWhen" => "CASE :caseWhen :else :end",
        ":separetors" => ":separator",
        ":equal" => "%s = %s",
        ":diff" => "%s != %s",
        ":if" => "IF( %s, %s, %s )",
        ":count" => "COUNT( %s )",
        ":sum" => "SUM( %s )",
        ":avg" => "AVG( %s )",
        ":max" => "MAX( %s )",
        ":min" => "MIN( %s )",
        ":concat" => "CONCAT( %s )",
    ];

    public function caseWhen(string $when, string $then): CaseWhen
    {
        return (new CaseWhen($this))->when($when, $then);
    }

    public function getExprList(): array
    {
        return $this->expList;
    }

    public function and (): self
    {
        $this->addExpression(":and");
        return $this;
    }

    public function or (): self
    {
        $this->addExpression(":or");
        return $this;
    }

    public function where(string $operator = null, mixed $value = null): self
    {
        $this->addExpression(":where", [
            ":column" => $this->getCol(),
            ":operator" => $operator,
            ":values" => $value
        ]);
        return $this;
    }

    public function in(string|array $values): self
    {
        $this->addExpression(":in", [
            ":column" => $this->getCol(),
            ":values" => $values,
        ]);
        return $this;
    }

    public function notIn(string|array $values): self
    {
        $this->addExpression(":notIn", [
            ":column" => $this->getCol(),
            ":values" => $values,
        ]);
        return $this;
    }

    public function between(mixed $value1, mixed $value2): self
    {
        $this->addExpression(":between", [
            ":column" => $this->getCol(),
            ":values_1" => $value1,
            ":values_2" => $value2,
        ]);
        return $this;
    }

    public function notBetween(mixed $value1, mixed $value2): self
    {
        $this->addExpression(":notBetween", [
            ":column" => $this->getCol(),
            ":values_1" => $value1,
            ":values_2" => $value2,
        ]);
        return $this;
    }

    public function like(string $value): self
    {
        $this->addExpression(":like", [
            ":column" => $this->getCol(),
            ":values" => $value
        ]);
        return $this;
    }

    public function notLike(string $value): self
    {
        $this->addExpression(":notLike", [
            ":column" => $this->getCol(),
            ":values" => $value
        ]);
        return $this;
    }

    public function isNull(): self
    {
        $this->addExpression(":isNull", [
            ":column" => $this->getCol()
        ]);
        return $this;
    }

    public function notNull(): self
    {
        $this->addExpression(":notNull", [
            ":column" => $this->getCol()
        ]);
        return $this;
    }

    public function equals(string|int $value): self
    {
        $this->addExpression(":equal", [
            ":column" => $this->getCol(),
            ":value" => $value
        ]);
        return $this;
    }

    public function diff(string|int $value): self
    {
        $this->addExpression(":diff", [
            ":column" => $this->getCol(),
            ":value" => $value
        ]);
        return $this;
    }

    public function if (string $condition, string|int $value1, string|int $value2): self
    {
        $this->addExpression(":if", [
            ":condition" => $condition,
            ":value_1" => $value1,
            ":value_2" => $value2
        ]);
        return $this;
    }

    public function count(string $column = "*"): self
    {
        $this->addExpression(":count", [
            ":column" => $column
        ]);
        return $this;
    }

    public function sum(string $column): self
    {
        $this->addExpression(":sum", [
            ":column" => $column
        ]);
        return $this;
    }

    public function avg(string $column): self
    {
        $this->addExpression(":avg", [
            ":column" => $column
        ]);
        return $this;
    }

    public function max(string $column): self
    {
        $this->addExpression(":max", [
            ":column" => $column
        ]);
        return $this;
    }

    public function min(string $column): self
    {
        $this->addExpression(":min", [
            ":column" => $column
        ]);
        return $this;
    }

    public function concat(string ...$column): self
    {
        $this->addExpression(":concat", [
            ":column" => implode(", ", $column)
        ]
        );
        return $this;
    }

    public function col(string $column): self
    {
        $this->field = $column;
        return $this;
    }

}
