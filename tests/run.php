<?php

require_once __DIR__ . "/../vendor/autoload.php";

use QueryBuilder\QueryBuilder;
use QueryBuilder\Connection\Connection;

$query = new QueryBuilder(
    new Connection(
        "127.0.0.1",
        "root",
        123,
        "robot"
    )
);


// dd($query->create()->table("users")->columns(function ($column) {
//      $column->add("id")->int(10)->primaryKey()->autoIncrement();
//      $column->add("name")->varchar(45)->notNull()->default("NULL");
//      $column->constraint("aaaaaa")->fk("bbbbb")->references("cccc", "dddd");
//      return $column;
// })->toSql());


$query->insert([
    "teste" => ":teste",
])