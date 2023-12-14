<?php

declare(strict_types=1);

namespace QueryBuilder\Contracts;
use QueryBuilder\Macro\Expressions\CaseWhen;

interface Expression
{
    public function resolve(): string;

    public function getParameters(): array;

    public function addParameterTo(string $notation, array $parameters): void;

    public function and (): self;

    public function or (): self;

    public function where(string $operator = null, mixed $value = null): self;

    public function in(string|array $values): self;

    public function notIn(string|array $values): self;

    public function between(mixed $value1, mixed $value2): self;

    public function notBetween(mixed $value1, mixed $value2): self;

    public function like(string $value): self;

    public function notLike(string $value): self;

    public function isNull(): self;

    public function notNull(): self;

    public function equal(string|int $value): self;

    public function diff(string|int $value): self;

    public function case(): CaseWhen;

    public function col(string $column): self;

    public function addExpression(string $statement, array $arguments = []): void;

}
