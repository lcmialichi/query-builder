<?php

namespace QueryBuilder\Connection;

use QueryBuilder\Exception\ConnectionException;

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

    /** @throws ConnectionException */
    public function createConnection(): void
    {
        if (!$this->driverExists($this->driver)) {
            throw ConnectionException::driverNotFound($this->driver);
        }

        $this->setConnection(new \PDO(
            $this->getDsn(),
            $this->getUser(),
            $this->getPassword()
        ));
    }

    public function disableAutoCommit(): void
    {
        $this->connection()->beginTransaction();
    }

    public function commit(): void
    {
        $this->connection()->commit();
    }

    public static function hasConnection(): bool
    {
        return isset(self::$connection);
    }

    public static function connection(): \PDO
    {
        if (!isset(self::$connection)) {
            throw ConnectionException::connectionNotEstablished();
        }
        return self::$connection;
    }

    public function driverExists(string $driver): bool
    {
        return in_array($driver, connectionDrivers());
    }

    private function setConnection(\PDO $connection): void
    {
        self::$connection = $connection;
    }

    private function getDsn(): string
    {
        return "{$this->driver}:host={$this->host};dbname={$this->database}";
    }

    private function getUser(): string
    {
        return $this->user;
    }

    private function getPassword(): string
    {
        return $this->password;
    }

    public function disconnect(): void
    {
        if($this->hasConnection()){
            unset(self::$connection);
        }
    }
}
