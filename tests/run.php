<?php

require_once __DIR__ . "/../vendor/autoload.php";

use QueryBuilder\QueryBuilder;
use QueryBuilder\Connection\Connection;

$qb = new QueryBuilder(
    new Connection(
        "127.0.0.1",
        "root",
        123,
        "teste"
    )
);

$xpr = QueryBuilder::expr()->if("name = name", "name", "id");

$query = $qb->select([
    "id" => "idAlias"
])->from("user")->limit(1)->offset(1)->where(
        QueryBuilder::expr()->if(QueryBuilder::expr("id")->equals(1)->and()->diff(
            QueryBuilder::expr()->caseWhen("id = 2", QueryBuilder::expr()->sum("id"))->else(QueryBuilder::expr()->count("id"))->end()
        ), "name", "id")
    )
    ->toSql();

dd($query);