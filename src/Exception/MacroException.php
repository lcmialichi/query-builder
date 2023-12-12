<?php

declare(strict_types=1);

namespace QueryBuilder\Exception;

use QueryBuilder\Contracts\Macro;

class MacroException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct("[QB Macro] " . $message);
    }

    public static function macroNotFound(string $macro): self
    {
        return new self(sprintf("Macro %s does not exist", $macro));
    }

    public static function macroNotInstance(string $macro): self
    {
        return new self(sprintf("Macro %s must be an instance of %s", $macro, Macro::class));
    }
}
