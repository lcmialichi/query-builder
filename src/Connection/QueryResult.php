<?php

declare(strict_types=1);

namespace QueryBuilder\Connection;

use Iterator;

class QueryResult
{
    private \PDOStatement $statement;

    public function __construct(
        private Connection $connection,
        private string $query,
        private array $params = []
    ) {
    }

    private function connection(): \PDO
    {
        return $this->connection->connection();
    }

    public function toSql(): string
    {
        return $this->query;
    }

    public function execute(): QueryResult
    {
        $this->statement = $this->connection()->prepare($this->query);
        $this->bind();
        $this->statement->execute();
        return $this;
    }

    public function bind(): QueryResult
    {
        foreach ($this->params as $param => $value) {
            $this->statement->bindValue($param, $value);
        }
        return $this;
    }

    public function fetch(): array
    {
        return $this->statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function fetchAssociative(): Iterator
    {
        return $this->statement->getIterator();
    }

    public function count(): int
    {
        return $this->statement->rowCount();
    }

}
