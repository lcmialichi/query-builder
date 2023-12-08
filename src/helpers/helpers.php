<?php

if (!function_exists("dd")) {
    function dd(...$items): void
    {
        foreach ($items as $item) {
            var_dump($item);
        }

        die;
    }
}


if (!function_exists('dot')) {
    /**
     * @param mixed $var
     * @return mixed
     */
    function dot(string $search, array|object $array): mixed
    {
        if (array_key_exists($search, $array)) {
            return $array[$search];
        }
        if (!str_contains($search, '.')) {
            return $array[$search] ?? null;
        }

        foreach (explode('.', $search) as $segment) {
            if (is_object($array) and isset($array->{$segment})) {
                $array = $array->{$segment};
                continue;
            }
            if (array_key_exists($segment, $array)) {
                $array = $array[$segment];
            } else {
                return null;
            }
        }

        return $array;
    }
}

if (!function_exists("configs")) {
    function configs(?string $item = null): mixed
    {
        $config = file_get_contents(__DIR__ . "/../Config/configuration.json");
        return !$item ? $config : dot($item, json_decode($config, true));
    }
}

if (!function_exists("connectionDrivers")) {
    function connectionDrivers(): array
    {
        return configs("connection.drivers");
    }
}
