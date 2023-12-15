<?php

declare(strict_types=1);

namespace QueryBuilder\Connection;

use Iterator;

class QueryResult
{
    private \PDOStatement $statement;

    private array $columns = [];

    public function __construct(
        private Connection $connection,
        private string $query,
        private array $params = []
    ) {
    }

    public function execute(): QueryResult
    {
        $this->statement = $this->connection()->prepare($this->query);
        if (!$this->statement) {
            throw new \Exception("Error to prepare statement");
        }

        $this->bind();
        $this->statement->execute();
        $this->columns = $this->getColumnsName();
        return $this;
    }

    private function connection(): \PDO
    {
        return $this->connection->connection();
    }

    public function toSql(): string
    {
        return $this->query;
    }

    public function params(): array
    {
        return $this->params;
    }

    private function bind(): QueryResult
    {
        foreach ($this->params as $param => $value) {
            $this->statement->bindValue($param, $value);
        }
        return $this;
    }

    public function fetchAll(): array
    {
        return $this->statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function fetchFunction(callable $callable): array
    {
        return $this->statement->fetchAll(\PDO::FETCH_FUNC, function (...$data) use ($callable) {
            return $callable(array_combine($this->columns, $data));
        });
    }

    public function fetchAssociative(): Iterator
    {
        return $this->statement->getIterator();
    }

    public function rollBack(): bool
    {
        return $this->connection()->rollBack();
    }

    public function commit(): void
    {
        $this->connection()->commit();
    }

    public function count(): int
    {
        return $this->statement->rowCount();
    }

    public function columnCount(): int
    {
        return $this->statement->columnCount();
    }

    public function lastId(): bool|string
    {
        return $this->connection()->lastInsertId();
    }

    public function getColumnMeta(int $column): array
    {
        return $this->statement->getColumnMeta($column);
    }

    public function getColumnsName(): array
    {
        $count = $this->columnCount();
        $index = 0;
        $columns = [];
        while ($index < $count) {
            $columns[] = $this->getColumnMeta($index)["name"];
            $index++;
        }
        return $columns;
    }

}
