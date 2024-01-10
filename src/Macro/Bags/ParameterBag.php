<?php

declare(strict_types=1);

namespace QueryBuilder\Macro\Bags;

class ParameterBag
{
    public function __construct(
        private array $parameters = []
    ) {
    }

    public function has(string $key): bool
    {
        return isset($this->parameters[$key]);
    }

    public function get(string $key): mixed
    {
        return $this->parameters[$key];
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function add(array $parameters): void
    {
        $this->parameters = array_merge($this->parameters, $parameters);
    }

    public function addParameters(array $parameters): void
    {
      $this->add($parameters);
    }

    public function addIntoParameter(string $key, mixed $value): void
    {
        $this->parameters[$key][] = $value;
    }

    public function setParameter(string $key, mixed $value): void
    {
        $this->parameters[$key] = $value;
    }

    public function addParameterTo(string $notation, array $parameters): void
    {
        $notation = explode(".", $notation);
        if (!is_null($notation)) {
            $reference = &$this->parameters;
            $count = count($notation);
            foreach ($notation as $key) {
                if (!isset($reference[$key])) {
                    $reference[$key] = [];
                }
                if ($count-- == 1) {
                    $reference[$key][] = $parameters;
                }
            }
        }
    }

    public function remove(string $key): void
    {
        unset($this->parameters[$key]);
    }
}
