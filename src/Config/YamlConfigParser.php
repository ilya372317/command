<?php

namespace Ilyaotinov\CLI\Config;

class YamlConfigParser implements ConfigParserInterface
{
    public const CONFIG_FILENAME = 'command.yaml';
    public const BASE_DIR_LEVEL = 2;

    public function getContentArray(): array
    {
        $configFullPath = $this->getConfigDirectory() . self::CONFIG_FILENAME;

        return yaml_parse_file($configFullPath);
    }

    public function get(string $key): array|string|int
    {
        $configArray = $this->getContentArray();
        return $configArray[$key];
    }

    public function getConfigDirectory(): string
    {
        return dirname(__DIR__, self::BASE_DIR_LEVEL) . '/config/';
    }
}