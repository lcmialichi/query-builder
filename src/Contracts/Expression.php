<?php

declare(strict_types=1);

namespace QueryBuilder\Contracts;

interface Expression
{
    public function resolve(): string;

    public function getParameters(): array;

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

    public function case(): self;

    public function when(string $value1, string $value2): self;

    public function else(mixed $value): self;

    public function end(): self;

}
