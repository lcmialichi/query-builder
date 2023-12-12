<?php

declare(strict_types=1);

namespace QueryBuilder\Enum;

enum Verbosity
{
    case VERBOSE;
    case LOW;

    public function isVerbose(): bool
    {
        return $this === self::VERBOSE;
    }

    public function isLow(): bool
    {
        return $this === self::LOW;
    }
}
