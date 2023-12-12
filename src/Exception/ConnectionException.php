<?php

declare(strict_types=1);

namespace QueryBuilder\Exception;

use Exception;

class ConnectionException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct("[QB Connection] " . $message);
    }

    public static function unableToEstablishConnection(string $message): self
    {
        return new self(sprintf("unable to establish established: %s", $message));
    }

    public static function connectionNotEstablished(): self
    {
        return new self("connection not established");
    }

    public static function driverNotFound(string $driver): self
    {
        return new self(sprintf("Driver %s does not exist", $driver));
    }

}
