<?php

declare(strict_types=1);

namespace QueryBuilder\Macro;

use  QueryBuilder\Macro\Statements\FromStatement;
use QueryBuilder\Contracts\Macro;
use QueryBuilder\Macro\Statement;
use QueryBuilder\Macro\Statements\WhereStatement;

class Delete extends Statement implements Macro
{
    use WhereStatement,
        FromStatement;
}
