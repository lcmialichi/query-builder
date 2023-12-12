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

$fetch = $qb->withRollBack()
    ->select(["teste" => "abc"])
    ->from("users")
    ->where($qb->expression("id")->between(1, 2)->notNull()->col("teste")->in([":IdDoUser", 2, 3]))
    ->orWhere("id", ">", 1)
    // ->addParam(":IdDoUser", 2020)
    ->join("users", "users.id = visits.user_id", "us")
    ->toSql();

dd($fetch);