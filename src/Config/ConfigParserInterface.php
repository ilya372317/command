<?php

namespace Ilyaotinov\CLI\Config;

use RuntimeException;

interface ConfigParserInterface
{
    /**
     * Get config value by key.
     *
     * @param  string  $key
     * @throws RuntimeException
     * @return array|string|int
     */
    public function get(string $key): array|string|int;

    /**
     * Return full path to directory, where config store.
     *
     * @return string
     */
    public function getConfigDirectory(): string;
}