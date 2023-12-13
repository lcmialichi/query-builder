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

$teste = $qb->update("user")->set(
    [
        "name" => ":value",
        "email" => "abc"
    ]
)->addParam(":value", "assa")->where($qb->expr()->select()
        ->case()->when("name", "teste")
        ->when("mais um", "oiutro")
        ->else("isso é um teste")
        ->end());
dd($teste->toSql());