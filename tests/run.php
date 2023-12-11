<?php

require_once __DIR__ . "/../vendor/autoload.php";

use QueryBuilder\Usuario;
use QueryBuilder\QueryBuilder;
use QueryBuilder\Connection\Connection;

$qb = new QueryBuilder(
    new Connection(
        "127.0.0.1",
        "root",
        123,
        ""
    )
);


$fetch = $qb->withRollBack()
    ->select()
    ->from("users")
    ->execute();
