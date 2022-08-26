<?php

namespace Ilyaotinov\CLI\CommandLoader;

use Command\Test\Command1;
use Ilyaotinov\CLI\Config\ConfigParserInterface;
use ReflectionException;

class CommandLoader
{
    private ConfigParserInterface $configParser;

    public function __construct(ConfigParserInterface $configParser)
    {
        $this->configParser = $configParser;
    }

    public function getCommandList(): array
    {
        $commandConfig = $this->getCommandConfig();
        $result = [];

        foreach ($commandConfig as $command) {
            $result[] = $this->makeClassObject($command['class']);
        }

        return $result;
    }

    /**
     * @throws ReflectionException
     */
    private function makeClassObject(string $class): object
    {
        $class = new \ReflectionClass($class);
        return $class->newInstance();
    }

    private function getCommandConfig(): array
    {
        return $this->configParser->get('commands');
    }
}