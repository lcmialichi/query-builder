<?php

declare(strict_types=1);

namespace QueryBuilder\Macro\Bags;

class ParameterBag
{
    public function __construct(
        private array $parameters = []
    ) {
    }

    private function add(array $parameters): void
    {
        $this->parameters[] = $parameters;
    }

    public function addParameterTo(string $notation, array $parameters): void
    {
        $notation = explode(".", $notation);
        if (!is_null($notation)) {
            $reference = &$this->$parameters;
            $i = count($notation);
            foreach ($notation as $key) {
                if (!isset($reference[$key])) {
                    $reference[$key] = [];
                }
                $reference = &$reference[$key];
                if ($i-- == 1) {
                    $reference[] = $parameters;
                }
            }
        }
    }
}
