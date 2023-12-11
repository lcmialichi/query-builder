<?php

require_once __DIR__ . "/../vendor/autoload.php";

use QueryBuilder\QueryBuilder;
use QueryBuilder\Connection\Connection;

$qb = new QueryBuilder(
    new Connection(
        "127.0.0.1",
        "root",
        123,
        "tim_visits"
    )
);


$success = $qb->select([
    "accomplished_km"
    ])
    ->from("visit")
    ->where("id", ">", ":ID")
    ->where("id", "<", ":ID")
    ->addParam(":ID",  1)
    ->execute()->toSql();

    dd($success);
// dd($success->fetch());