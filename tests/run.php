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
    ->where(fn($exp) => $exp->between("accomplished_km", 1, 2000))
    ->addParam(":ID",  1)
    ->toSql();

    dd($success);
// dd($success->fetch());