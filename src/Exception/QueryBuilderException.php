<?php

declare(strict_types=1);

namespace QueryBuilder\Exception;

use Exception;

class QueryBuilderException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct("[QB] " . $message);
    }

    public static function macroNotFound(string $macro): self
    {
        return new self(sprintf("Macro %s does not exist", $macro));
    }
}
