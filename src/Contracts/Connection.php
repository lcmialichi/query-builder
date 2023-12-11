<?php

namespace QueryBuilder\Contracts;

interface Connection
{
    public function createConnection(): void;

    public function hasConnection(): bool;

    public function disableAutoCommit(): void;

    public function commit(): void;
}

