<?php

require_once __DIR__ . "/../vendor/autoload.php";

use QueryBuilder\QueryBuilder;
use QueryBuilder\Connection\Connection;

$query = new QueryBuilder(
    new Connection(
        "127.0.0.1",
        "root",
        123,
        "framework_project"
    )
);
