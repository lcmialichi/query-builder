<?php

declare(strict_types=1);

namespace QueryBuilder\Macro\Schemma;

use QueryBuilder\Macro\Schemma\Column;

class Columns
{
    private array $columns = [];

    /** @var array<Constraint>  */
    private array $constraint = [];

    public function &add(string $name): Column
    {
        $this->columns[$id = uniqid()] = new Column($name);
        return $this->columns[$id];
    }

    public function &constraint(string $name): Constraint
    {
        $this->constraint[$id = uniqid()] = new Constraint($name);
        return  $this->constraint[$id];
    }

    public function getColumns(): array
    {
        return array_map(fn($column) => $column?->toArray(), $this->columns);
    }

    public function getConstraints(): array
    {
        return array_map(fn($constraint) => $constraint?->toArray(), $this->constraint);
    }

}
