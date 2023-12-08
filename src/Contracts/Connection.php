<?php

namespace QueryBuilder\Contracts;

interface Connection
{
    public function execute(string $statement): void;
}

