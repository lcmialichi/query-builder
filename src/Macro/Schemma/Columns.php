<?php

declare(strict_types=1);

namespace QueryBuilder\Macro\Schemma;

use QueryBuilder\Macro\Schemma\Column;

class Columns
{
    private array $columns = [];
    private ?Constraint $constraint = null;

    public function &add(string $name): Column
    {
        $this->columns[$id = uniqid()] = new Column($name);
        return $this->columns[$id];
    }

    public function &constraint(string $name): Constraint
    {
        $this->constraint = new Constraint($name);
        return $this->constraint;
    }

    public function getColumns(): array
    {
        return array_map(fn($column) => $column?->toArray(), $this->columns);
    }

    public function getConstraints(): array
    {
        return $this->constraint?->toArray() ?? [];
    }

}
