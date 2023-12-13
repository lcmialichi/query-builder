<?php

declare(strict_types=1);

namespace QueryBuilder\Macro;

use QueryBuilder\Macro\Expressions\Where;
use QueryBuilder\Macro\Expressions\Select;

class Expression
{
    public static function where(string $col): Where
    {
        return new Where($col);
    }

    public static function select(): Select
    {
        return new Select();
    }
}
