<?php

namespace Ilyaotinov\CLI\Config;

use Ilyaotinov\CLI\Helper\ConfigHelper;
use RuntimeException;

class YamlConfigParser implements ConfigParserInterface
{
    public const CONFIG_FILENAME = 'command.yaml';
    public const BASE_DIR_LEVEL = 2;

    /**
     * @return array
     * @throws RuntimeException
     */
    private function getContentArray(): array
    {
        $configFullPath = $this->getConfigDirectory() . self::CONFIG_FILENAME;
        $configNotExist = !file_exists($configFullPath);

        if ($configNotExist) {
            ConfigHelper::createCommandConfig();
            fwrite(STDOUT, 'You need fill config file. Check it on path: ' . $configFullPath . PHP_EOL);
            exit();
        }

        $configContent = yaml_parse_file($configFullPath);

        $configNotFound = !file_exists($configFullPath);
        $configIsEmpty = !isset($configContent);

        if ($configNotFound) {
            throw new RuntimeException('Config file not found,  it was try found at: ' . $configFullPath);
        }

        if ($configIsEmpty) {
            throw new RuntimeException('Config file empty');
        }

        return $configContent;
    }

    /**
     * @param string $key
     * @return array|string|int
     * @throws RuntimeException
     */
    public
    function get(string $key): array|string|int
    {
        $configArray = $this->getContentArray();
        $keyNotFound = !isset($configArray[$key]);

        if ($keyNotFound) {
            throw new RuntimeException('key {{' . $key . '}} not found in config file');
        }

        return $configArray[$key];
    }

    public
    function getConfigDirectory(): string
    {

        return dirname(__DIR__, self::BASE_DIR_LEVEL) . '/config/';
    }
}