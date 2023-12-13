<?php

declare(strict_types=1);

namespace QueryBuilder\Contracts;

interface Expression
{
    public function resolve(): string;

    public function getParameters(): array;
}
