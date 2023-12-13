<?php

declare(strict_types=1);

namespace QueryBuilder\Macro\Expressions;

use QueryBuilder\Contracts\Expression;

class Select extends ExpressionOrchestrator implements Expression
{
    private array $expList = [
        "case" => "CASE :when :else :end",
        "when" => "WHEN %s THEN %s",
        "else" => "ELSE :else",
        "end" => "END"
    ];

    public function getExprList(): array
    {
        return $this->expList;
    }

    public function case (): self
    {
        $this->addExpression(
            $this->getExpressionStatement(__FUNCTION__), [
                ":when" => []
            ]
        );

        return $this;
    }

    public function when(string $when = null, mixed $then = null): self
    {
        $uniqId = uniqid();
        $parameters = [
            ":when_$uniqId" => $when,
            ":then_$uniqId" => $then,
        ];

        $this->addParameterTo(
            ":when", [
                [
                    "statement" => $this->replaceOnStatement(
                        $this->getExpressionStatement("when"),
                        $parameters
                    ),
                    ...$parameters
                ]
            ]
        );
        return $this;

    }

    public function else (mixed $else = null): self
    {
        $this->addParameters([
            ":else" => [
                [
                    "statement" => $this->getExpressionStatement(__FUNCTION__),
                    ":else" => $else

                ]
            ]]);
        return $this;
    }

    public function end(): self
    {
        $this->addParameters([
            ":end" => [
                [
                    "statement" => $this->getExpressionStatement(__FUNCTION__),

                ]
            ]]);
        return $this;
    }

}
