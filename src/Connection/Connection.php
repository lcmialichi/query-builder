<?php

namespace QueryBuilder\Connection;

class Connection implements \QueryBuilder\Contracts\Connection
{
    protected static \PDO $connection;

    public function __construct(
        private ?string $host,
        private ?string $user,
        private ?string $password,
        private ?string $database,
        private ?string $driver = "mysql"
    ) {
    }

    public function createConnection(): void
    {
        if ($this->driverExists($this->driver)) {
            self::$connection = new \PDO(
                "{$this->driver}:host={$this->host};dbname={$this->database}",
                $this->user,
                $this->password
            );
            return;
        }

        throw new \Exception("Driver `{$this->driver}` does not exist");
    }

    public function disableAutoCommit(): void
    {
        $this->connection()->beginTransaction();
    }

    public function commit(): void
    {
        $this->connection()->commit();
    }

    public function hasConnection(): bool
    {
        return isset(self::$connection);
    }

    public static function connection(): \PDO
    {
        if (!isset(self::$connection)) {
            throw new \Exception("Connection not created");
        }
        return self::$connection;
    }

    public function driverExists(string $driver): bool
    {
        return in_array($driver, connectionDrivers());
    }
}
