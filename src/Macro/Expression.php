<?php

declare(strict_types=1);

namespace QueryBuilder\Macro;

class Expression
{
    private $parameter = [];

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

    private array $expressionUsage = [];

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

    public function resolve(): string
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

    private function getLastExpression(): string
    {
        return end($this->expressionUsage);
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
