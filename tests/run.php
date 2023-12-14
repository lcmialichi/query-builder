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
    $qb->expr()->case()->when("9", "8")->when("7", "6")->else("5")->end()
    ->case()->when("0", "id")->when("1", "2")->else("3")->end()
)->from("user");
dd($teste->toSql());