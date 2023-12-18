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
    "id" => "identifier",
    "name" => "userName",
    "birth_date" => "birthDate"
])
->from('users', "u")
->join("address", "u.address_id = a.id", "a")
->where('id', '=', ':Id')
->orWhere("birth_date", ">", date("Y-m-d H:i:s"))
->limit(10)
->offset(1)
    ->toSql();

dd($query);