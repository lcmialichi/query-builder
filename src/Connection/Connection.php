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
        $this->createConnection();
    }
    
    private function createConnection(): void
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

    public static function connection(): \PDO
    {
        return self::$connection;
    }

    public function driverExists(string $driver): bool
    {
        return in_array($driver, connectionDrivers());
    }
}
