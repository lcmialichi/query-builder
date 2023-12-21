<?php

namespace QueryBuilder\Macro\Builder;

use QueryBuilder\Macro\Expressions\Expression;

class Builder
{
    private string $query = "";
    private const PRIORITY_LIST = [':join', ':where', ':group', ':order', ':limit', ':offset'];

    public function __construct(
        private BaseStructure $baseStrucute
    ) {
        $this->addIntoQuery($this->baseStrucute->getStructure());
    }

    public function build(): self
    {
        foreach (self::PRIORITY_LIST as $item) {
            $this->addIntoQuery($this->baseStrucute->get($item));
        }

        $this->setQuery(
            $this->replaceOptions($this->getQuery(), $this->baseStrucute->getQueryParameters())
        );

        return $this;
    }

    private function addIntoQuery(string $query)
    {
        $this->query .= $query;
    }

    private function setQuery(string $query): void
    {
        $this->query = $query;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    /** @param array<mixed> $parameters */
    private function replaceOptions(string $statement, array $paramemters): string
    {
        foreach ($this->getMatches($statement) as $context) {
            $context = trim($context);
            $itemToReplace =
                $this->baseStrucute->prepareValue(
                    $context,
                    $this->prepareReplements(dot($context, $paramemters))
                );

            $statement = preg_replace('/' . $context . '/', $itemToReplace, $statement, 1);
        }

        return $statement;
    }

    private function prepareReplements(mixed $itemToReplace): array
    {
        $build = [];
        if (!is_array($itemToReplace)) {
            $itemToReplace = [$itemToReplace];
        }
        foreach ($itemToReplace as $key => $value) {
            $build[$key] = $this->resolveStatement($value);
            
        }
        return $build;
    }

    private function resolveStatement(mixed $value): mixed
    {
        if (isset($value['statement'])) {
            return $this->replaceOptions($value['statement'], $value);
        }

        if (is_string($value) && $this->isSerialized($value)) {
            $unserialized = unserialize($value);
            if ($unserialized instanceof Expression) {
                return $this->replaceOptions($unserialized->resolve(), $unserialized->getParametersAsArray());
            }
        }

        return $value;
    }


    private function getMatches(string $statement): array
    {
        preg_match_all("/:[^\s]*\s?/", $statement, $matches);
        return $matches[0];
    }

    /**
     * I know using @ on standard php functions is not a good idea but
     * will work for these purposes
     */
    private function isSerialized(string $string): bool
    {
        return (@unserialize($string) !== false);
    }

}
