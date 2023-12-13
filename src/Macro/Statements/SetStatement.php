<?php

declare(strict_types=1);

namespace QueryBuilder\Macro\Statements;

trait SetStatement
{
    public function set(array $items): self
    {
        foreach ($items as $item => $value) {
            $this->addSet($item, $value);
        }

        return $this;
    }

    public function addSet(string $field, mixed $updateValue): self
    {
        $this->addStatementOption(":sets", [
            "statement" => ":set = :value",
            ":set" => $field,
            ":value" => $updateValue
        ]);
        return $this;
    }
}
