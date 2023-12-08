<?php

namespace QueryBuilder\Connection;

class Connection implements \QueryBuilder\Contracts\Connection
{
    protected \PDO $connection;

    public function __construct(
        private ?string $host,
        private ?string $user,
        private ?string $password,
        private ?string $database,
        private ?string $driver = "mysql"
    ) {
        $this->createConnection();
    }

    public function execute(string $statement): void
    {
        $this->connection()->query($statement)->execute();
    }

    private function createConnection(): void
    {
        if ($this->driverExists($this->driver)) {
            $this->connection = new \PDO(
                "{$this->driver}:host={$this->host};dbname={$this->database}",
                $this->user,
                $this->password
            );
            return;
        }

        throw new \Exception("Driver `{$this->driver}` does not exist");
    }

    private function connection(): \PDO
    {
        return $this->connection;
    }

    public function driverExists(string $driver): bool
    {
        return in_array($driver, connectionDrivers());
    }

    public function fetch(){
        
    }


}
