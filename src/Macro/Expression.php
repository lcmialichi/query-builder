<?php

declare(strict_types=1);

namespace QueryBuilder\Macro;

class Expression
{
    private $parameter = [];

    private array $expList = [
        "and" => "and",
        "or" => "or",
        "where" => "%s %s %s",
        "in" => "%s in ( %s )",
        "notIn" => "%s not in ( %s )",
        "between" => "%s between %s and %s",
        "notBetween" => "%s not between %s and %s",
        "like" => "%s like %s",
        "notLike" => "%s not like %s",
        "isNull" => "%s is null",
        "notNull" => "%s is not null",
    ];

    private array $expressionUsage = [];

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

    public function where(string $column, string $operator = null, mixed $value = null): self
    {
        $id = uniqid();
        $this->addExpression(
            $this->getExpressionStatement(__FUNCTION__), [
                ":column_$id" => $column,
                ":operator_$id" => $operator,
                ":value_$id" => $value
            ]
        );

        return $this;
    }

    public function in(string $column, string|array $values): self
    {
        $id = uniqid();
        $this->addExpression(
            $this->getExpressionStatement(__FUNCTION__), [
                ":column_$id" => $column,
                ":values_$id" => implode(', ',$values),
            ]
        );

        return $this;
    }

    public function notIn(string $column, string|array $values): self
    {
        $id = uniqid();
        $this->addExpression(
            $this->getExpressionStatement(__FUNCTION__), [
                ":column_$id" => $column,
                ":values_$id" => implode(', ',$values),
            ]
        );

        return $this;
    }

    public function between(string $column, mixed $value1, mixed $value2): self
    {
        $id = uniqid();
        $this->addExpression(
            $this->getExpressionStatement(__FUNCTION__), [
                ":column_$id" => $column,
                ":value1_$id" => $value1,
                ":value2_$id" => $value2,
            ]
        );
        return $this;
    }

    public function notBetween(string $column, mixed $value1, mixed $value2): self
    {
        $id = uniqid();
        $this->addExpression(
            $this->getExpressionStatement(__FUNCTION__), [
                ":column_$id" => $column,
                ":value1_$id" => $value1,
                ":value2_$id" => $value2,
            ]
        );
        return $this;
    }

    public function like(string $column, string $value): self
    {
        $id = uniqid();
        $this->addExpression(
            $this->getExpressionStatement(__FUNCTION__), [
                ":column_$id" => $column,
                ":value_$id" => $value
            ]
        );
        return $this;
    }

    public function notLike(string $column, string $value): self
    {
        $id = uniqid();
        $this->addExpression(
            $this->getExpressionStatement(__FUNCTION__), [
                ":column_$id" => $column,
                ":value_$id" => $value
            ]
        );
        return $this;
    }

    public function isNull(string $column): self
    {
        $id = uniqid();
        $this->addExpression(
            $this->getExpressionStatement(__FUNCTION__), [
                ":column_$id" => $column
            ]
        );
        return $this;
    }

    public function notNull(string $column): self
    {
        $id = uniqid();
        $this->addExpression(
            $this->getExpressionStatement(__FUNCTION__), [
                ":column_$id" => $column
            ]
        );
        return $this;
    }


    private function expressionExists(string $name): bool
    {
        return isset($this->expList[$name]);
    }

    private function getExpressionStatement(string $name): string
    {
        if (!$this->expressionExists($name)) {
            throw new \Exception("Expression `{$name}` does not exist");
        }

        return $this->expList[$name] ?? "";

    }

    private function addExpression(string $statement, array $arguments = []): void
    {
        if ($statement != "and" && $statement != "or") {
            $this->addAndExpressionIfNotExists();

        }
        $this->expressionUsage[] = sprintf($statement, ...array_keys($arguments));
        $this->addParameters($arguments);
    }

    public function getExpression(): string
    {
        return implode(" ", $this->expressionUsage);
    }

    public function getParameters(): array
    {
        return $this->parameter;
    }

    private function addParameters(array $parameters): void
    {
        $this->parameter = array_merge($this->parameter, $parameters);
    }

    public function hasExpression(): bool
    {
        return !empty($this->expressionUsage);
    }

    public function getLastExpression(): string
    {
        return end($this->expressionUsage);
    }

    public function addAndExpressionIfNotExists(): void
    {
        if (!$this->hasExpression()) {
            return;
        }

        $lastExpression = $this->getLastExpression();
        if ($lastExpression == "and" || $lastExpression == "or") {
            return;
        }

        $this->expressionUsage[] = "and";
    }
}
