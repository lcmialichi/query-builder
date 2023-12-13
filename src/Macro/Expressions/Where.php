<?php

declare(strict_types=1);

namespace QueryBuilder\Macro\Expressions;

use QueryBuilder\Contracts\Expression;
use QueryBuilder\Macro\Expressions\ExpressionOrchestrator;

class Where extends ExpressionOrchestrator implements Expression
{
    private array $expList = [
        "and" => "AND",
        "or" => "OR",
        "where" => "%s %s %s",
        "in" => "%s IN ( %s )",
        "notIn" => "%s NOT IN ( %s )",
        "between" => "%s BETWEEN %s AND %s",
        "notBetween" => "%s NOT BETWEEN %s AND %s",
        "like" => "%s LIKE %s",
        "notLike" => "%s NOT LIKE %s",
        "isNull" => "%s IS NULL",
        "notNull" => "%s IS NOT NULL",
    ];

    public function getExprList(): array
    {
        return $this->expList;
    }

    public function __construct(private ?string $field = null)
    {
    }

    public function and ()
    {
        $this->addExpression($this->getExpressionStatement(__FUNCTION__));
        return $this;
    }

    public function or ()
    {
        $this->addExpression($this->getExpressionStatement(__FUNCTION__));
        return $this;
    }

    public function where(string $operator = null, mixed $value = null): self
    {
        $id = uniqid();
        $this->addExpression(
            $this->getExpressionStatement(__FUNCTION__), [
                ":column_$id" => $this->getCol(),
                ":operator_$id" => $operator,
                ":values_$id" => $value
            ]
        );

        return $this;
    }

    public function in(string|array $values): self
    {
        $id = uniqid();
        $this->addExpression(
            $this->getExpressionStatement(__FUNCTION__), [
                ":column_$id" => $this->getCol(),
                ":values_$id" => $values,
            ]
        );

        return $this;
    }

    public function notIn(string|array $values): self
    {
        $id = uniqid();
        $this->addExpression(
            $this->getExpressionStatement(__FUNCTION__), [
                ":column_$id" => $this->getCol(),
                ":values_$id" => $values,
            ]
        );

        return $this;
    }

    public function between(mixed $value1, mixed $value2): self
    {
        $id = uniqid();
        $this->addExpression(
            $this->getExpressionStatement(__FUNCTION__), [
                ":column_$id" => $this->getCol(),
                ":values_1_$id" => $value1,
                ":values_2_$id" => $value2,
            ]
        );
        return $this;
    }

    public function notBetween(mixed $value1, mixed $value2): self
    {
        $id = uniqid();
        $this->addExpression(
            $this->getExpressionStatement(__FUNCTION__), [
                ":column_$id" => $this->getCol(),
                ":values_1_$id" => $value1,
                ":values_2_$id" => $value2,
            ]
        );
        return $this;
    }

    public function like(string $value): self
    {
        $id = uniqid();
        $this->addExpression(
            $this->getExpressionStatement(__FUNCTION__), [
                ":column_$id" => $this->getCol(),
                ":values_$id" => $value
            ]
        );
        return $this;
    }

    public function notLike(string $value): self
    {
        $id = uniqid();
        $this->addExpression(
            $this->getExpressionStatement(__FUNCTION__), [
                ":column_$id" => $this->getCol(),
                ":values_$id" => $value
            ]
        );
        return $this;
    }

    public function isNull(): self
    {
        $id = uniqid();
        $this->addExpression(
            $this->getExpressionStatement(__FUNCTION__), [
                ":column_$id" => $this->getCol()
            ]
        );
        return $this;
    }

    public function notNull(): self
    {
        $id = uniqid();
        $this->addExpression(
            $this->getExpressionStatement(__FUNCTION__), [
                ":column_$id" => $this->getCol()
            ]
        );
        return $this;
    }

    protected function addExpression(string $statement, array $arguments = []): void
    {
        if ($statement != "and" && $statement != "or") {
            $this->addAndExpressionIfNotExists();

        }
        $this->expressionUsage[] = sprintf($statement, ...array_keys($arguments));
        $this->addParameters($arguments);
    }


    private function addAndExpressionIfNotExists(): void
    {
        if (!$this->hasExpression()) {
            return;
        }

        $lastExpression = $this->getLastExpression();
        if ($lastExpression == "AND" || $lastExpression == "OR") {
            return;
        }

        $this->expressionUsage[] = "AND";
    }

    public function col(string $column): self
    {
        $this->field = $column;
        return $this;
    }

    private function getCol(): string
    {
        return $this->field;
    }
}
