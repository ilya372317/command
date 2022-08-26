<?php

namespace Ilyaotinov\CLI\CommandLoader;

use Ilyaotinov\CLI\AbstractCommand;
use Ilyaotinov\CLI\Config\ConfigParserInterface;
use Ilyaotinov\CLI\Config\YamlConfigParser;
use ReflectionException;

class CommandLoader
{
    private ConfigParserInterface $configParser;

    public function __construct(ConfigParserInterface $configParser)
    {
        $this->configParser = $configParser;
        $this->createCommandConfig();
    }

    public function getCommandList(): array
    {
        $commandConfig = $this->getCommandConfig();
        $result = [];

        foreach ($commandConfig as $command) {
            try {
                $this->checkCommandConfig($command);
                $result[] = $this->makeCommandObject($command['class']);
            } catch (\Exception $exception) {
                //TODO: Make write class
                fwrite(STDIN, $exception->getMessage().PHP_EOL);
                exit();
            }
        }

        return $result;
    }

    private function checkCommandConfig(array $commandConf): void
    {
        $classConfNotSet = !isset($commandConf['class']);
        $nameConfNotSet = !isset($commandConf['name']);
        $descriptionConfNotSet = !isset($commandConf['description']);

        if ($classConfNotSet) {
            $this->writeDataAndExit('Class command not set in config file');
        }
        if ($nameConfNotSet) {
            $this->writeDataAndExit('Name of command not set in config file');
        }
        if ($descriptionConfNotSet) {
            $this->writeDataAndExit('Description of command not set in config file');
        }
    }

    //TODO: Make write class
    private function writeDataAndExit(string $data): void
    {
        fwrite(STDIN, $data . PHP_EOL);
        exit();
    }

    /**
     * @throws ReflectionException
     */
    private function makeCommandObject(string $class): AbstractCommand
    {
        $class = new \ReflectionClass($class);

        return $class->newInstance();
    }

    private function getCommandConfig(): ?array
    {
        try {
            $commandConfigData = $this->configParser->get('commands');
        } catch (\RuntimeException $exception) {
            //TODO: Make writer class
            fwrite(STDIN, $exception->getMessage().PHP_EOL);
            exit();
        }

        return $commandConfigData;
    }

    //TODO: replace to helper class
    private function createCommandConfig(): void
    {
        $basedir = dirname(__DIR__, YamlConfigParser::BASE_DIR_LEVEL).'/config';
        if (!is_dir($basedir)) {
            mkdir($basedir);
        }

        $configExist = file_exists($basedir.'/'.'command.yaml');

        if (!$configExist) {
            $yamlData = yaml_emit([
                'commands' => [
                    "TestCommand" => [
                        "class" => 'App\\AbstractCommand\\TestCommand',
                        "name" => 'test-command',
                        "description" => 'write your commands and put information about them there'
                    ]
                ]
            ], YAML_UTF8_ENCODING);

            $filename = $basedir.'/'.'command.yaml';
            $file = new \SplFileObject($filename, 'w+', '');
            $file->fwrite($yamlData);
            $file->rewind();
            $file->fflush();

            $this->writeDataOnEveryLineInFile($filename, '#');
        }
    }

    //TODO: Replace to helper class
    private function writeDataOnEveryLineInFile(string $filename, string $data): void
    {
        $originalFilename = $filename;

        $originalFileObject = new \SplFileObject($originalFilename);

        $tempFilename = tempnam(sys_get_temp_dir(), 'php_prepend_');

        while (!$originalFileObject->eof()) {
            $currentOriginalFileString = $originalFileObject->fgets();
            if (strlen(trim($currentOriginalFileString)) > 0) {
                file_put_contents($tempFilename, $data, FILE_APPEND);
            }

            file_put_contents($tempFilename, $currentOriginalFileString, FILE_APPEND);
            $originalFileObject->next();
        }
        unlink($originalFilename);
        rename($tempFilename, $originalFilename);
    }
}