<?php

require_once __DIR__ . "/../vendor/autoload.php";

use QueryBuilder\QueryBuilder;
use QueryBuilder\Connection\Connection;

$queryBuilder = new QueryBuilder(
    new Connection(
        "127.0.0.1",
        "root",
        123,
        null
    )
);


$queryBuilder->select(["field" => "teste", "oi" => "otoTeste"])
    ->from("teste", "oi")
    ->where("oi.id", "=", "123")
    ->join("teste", "oi", "oi.id = 123")
    ->leftJoin("mais_um_teste", "alias", "alias.id = 123")
    ->groupBy("teste")
    ->groupBy("teste")
    ->execute();


// $queryBuilder->update("tabela")->set([
//     "campo" => "valor",
//     "campo1" => "valor",
//     "campo2" => "valor",
//     "campo3" => "valor",
//     "campo4" => "valor",
// ])->execute();