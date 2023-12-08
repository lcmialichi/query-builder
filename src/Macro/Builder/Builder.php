<?php

namespace QueryBuilder\Macro\Builder;

class Builder
{
    private string $query = "";
    private const PRIORITY_LIST = [':join', ':where', ':group', ':order'];

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
            $this->replaceOptions($this->getQuery(), $this->baseStrucute->getParameters())
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

    private function replaceOptions(string $statemnt, array $paramemters): string
    {
        preg_match_all("/:[^\s]*\s?/", $statemnt, $matches);
        foreach ($matches[0] as $replace) {
            $replace = trim($replace);
            $data = dot($replace, $paramemters);
            if (is_array($data)) {
                $build = [];
                foreach ($data as $key => $value) {
                    $build[$key] = $value;
                    if (isset($value['statement'])) {
                        $build[$key] = $this->replaceOptions($value['statement'], $value);
                    }
                }
                $data = implode($this->baseStrucute->getDelimiter($replace), $build);
            }
            $statemnt = str_replace($replace, $data, $statemnt);
        }

        return $statemnt;
    }

}
