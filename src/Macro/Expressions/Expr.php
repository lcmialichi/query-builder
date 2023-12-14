<?php

declare(strict_types=1);

namespace QueryBuilder\Macro\Expressions;

use QueryBuilder\Macro\Statement;
use QueryBuilder\Contracts\Expression;
use QueryBuilder\Exception\ExpressionException;
use QueryBuilder\Macro\Expressions\ExpressionOrchestrator;

class Expr extends ExpressionOrchestrator implements Expression
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
    ];

    private array $parameters = [];

    public function case (): CaseWhen
    {
        return new CaseWhen($this);
    }

    public function getExprList(): array
    {
        return $this->expList;
    }

    public function __construct(private ?string $field = null, private Statement &$statement)
    {
        parent::__construct($statement);
    }

    public function and (): self
    {
        $this->addExpression($this->getExpressionStatement(":and"));
        return $this;
    }

    public function or (): self
    {
        $this->addExpression($this->getExpressionStatement(":or"));
        return $this;
    }

    public function where(string $operator = null, mixed $value = null): self
    {
        $this->addExpression(
            $this->getExpressionStatement(":where"), [
                ":column" => $this->getCol(),
                ":operator" => $operator,
                ":values" => $value
            ]
        );

        return $this;
    }

    public function in(string|array $values): self
    {
        $this->addExpression(
            $this->getExpressionStatement(":in"), [
                ":column" => $this->getCol(),
                ":values" => $values,
            ]
        );

        return $this;
    }

    public function notIn(string|array $values): self
    {
        $this->addExpression(
            $this->getExpressionStatement(":notIn"), [
                ":column" => $this->getCol(),
                ":values" => $values,
            ]
        );

        return $this;
    }

    public function between(mixed $value1, mixed $value2): self
    {
        $this->addExpression(
            $this->getExpressionStatement(":between"), [
                ":column" => $this->getCol(),
                ":values_1" => $value1,
                ":values_2" => $value2,
            ]
        );
        return $this;
    }

    public function notBetween(mixed $value1, mixed $value2): self
    {
        $this->addExpression(
            $this->getExpressionStatement(":notBetween"), [
                ":column" => $this->getCol(),
                ":values_1" => $value1,
                ":values_2" => $value2,
            ]
        );
        return $this;
    }

    public function like(string $value): self
    {
        $this->addExpression(
            $this->getExpressionStatement(":like"), [
                ":column" => $this->getCol(),
                ":values" => $value
            ]
        );
        return $this;
    }

    public function notLike(string $value): self
    {
        $this->addExpression(
            $this->getExpressionStatement(":notLike"), [
                ":column" => $this->getCol(),
                ":values" => $value
            ]
        );
        return $this;
    }

    public function isNull(): self
    {
        $this->addExpression(
            $this->getExpressionStatement(":isNull"), [
                ":column" => $this->getCol()
            ]
        );
        return $this;
    }

    public function notNull(): self
    {
        $this->addExpression(
            $this->getExpressionStatement(":notNull"), [
                ":column" => $this->getCol()
            ]
        );
        return $this;
    }

    public function equal(string|int $value): self
    {
        $this->addExpression(
            $this->getExpressionStatement(":equal"), [
                ":column" => $this->getCol(),
                ":value" => $value
            ]
        );
        return $this;
    }

    public function diff(string|int $value): self
    {
        $this->addExpression(
            $this->getExpressionStatement(":diff"), [
                ":column" => $this->getCol(),
                ":value" => $value
            ]
        );
        return $this;
    }

    public function col(string $column): self
    {
        $this->field = $column;
        return $this;
    }

    private function getCol(): string
    {
        if (isset($this->field)) {
            return $this->field;
        }

        throw ExpressionException::columnNotSet(__CLASS__, __METHOD__);
    }
}
