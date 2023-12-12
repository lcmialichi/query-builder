<?php

namespace QueryBuilder\Contracts;

interface Connection
{
    public function createConnection(): void;

    public function disconnect(): void;

    public static function hasConnection(): bool;

    public function disableAutoCommit(): void;

    public function commit(): void;
}

