<?php

namespace Ilyaotinov\CLI\Config;

interface ConfigParserInterface
{
    /**
     * Parse all config contents as associative array.
     *
     * @return string
     */
    public function getContentArray(): array;

    /**
     * Get config value by key.
     *
     * @param string $key
     * @return array|string
     */
    public function get(string $key): array|string|int;

    /**
     * Return full path to directory, where config store.
     *
     * @return string
     */
    public function getConfigDirectory(): string;
}