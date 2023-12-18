<?php

declare(strict_types=1);

namespace QueryBuilder\Macro\Expressions;

use QueryBuilder\Macro\Expressions\Expression;

class CaseWhen
{
    private $statement = [
        ":caseWhen" => "CASE :when :else :end",
        ":when" => "WHEN :when THEN :then",
        ":else" => "ELSE :else",
        ":end" => "END"
    ];

    private $parameters = [];

    public function __construct(private Expression &$expression)
    {
        $this->parameters = [
            "statement" => $this->statement[":caseWhen"],
            ":when" => [],
            ":else" => [],
            ":end" => []
        ];
    }

    public function when(string $value, string $then): self
    {
        $this->parameters[":when"][] = [
            "statement" => $this->statement[":when"],
            ":when" => $value,
            ":then" => $then
        ];
        return $this;
    }

    public function else (string $value): self
    {
        $this->parameters[":else"][] = [
            "statement" => $this->statement[":else"],
            ":else" => $value
        ];
        return $this;
    }

    public function end(): Expression
    {
        $this->parameters[":end"][] = $this->statement[":end"];
        $this->expression->addExpression(":caseWhen");
        $this->expression->addParameterTo(":caseWhen", $this->parameters);
        return $this->expression;
    }
}
