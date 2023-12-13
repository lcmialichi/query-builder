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

$teste = $qb->select(
    $qb->expr()
        ->case()
        ->when("teste", "id")
        ->when("teste", "teste")
        ->else("teste")
        ->end()
)->from("user");
dd($teste->toSql());