<?php

declare(strict_types=1);

namespace QueryBuilder\Macro;

use QueryBuilder\QueryBuilder;
use QueryBuilder\Contracts\Macro;

class Update extends Statement implements Macro
{
    public function __construct(QueryBuilder $queryBuilder, string $table)
    {
        $this->setStatementOption(":table.name", $table);
        parent::__construct($queryBuilder);
    }

    public function set(array $items): self
    {
        foreach ($items as $item => $value) {
            $this->addSet($item, $value);
        }

        return $this;
    }

    public function addSet(string $field, mixed $updateValue): self
    {
        $this->addStatementOption(":fields", [
            "statement" => ":field = :value",
            ":field" => $field,
            ":value" => $updateValue
        ]);
        return $this;
    }


}
