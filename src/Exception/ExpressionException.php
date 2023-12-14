<?php

declare(strict_types=1);

namespace QueryBuilder\Exception;

class ExpressionException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct("[Expression] " . $message);
    }

    public static function columnNotFound(string $collumn): self
    {
        return new self(sprintf("Collumn %s does not exist", $collumn));
    }

    public static function columnNotSet(string $method, string $class): self
    {
        return new self(sprintf("Column must be set in %s::%s", $class, $method));
    }
}
