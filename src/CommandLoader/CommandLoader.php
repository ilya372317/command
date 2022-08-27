<?php

namespace Ilyaotinov\CLI\CommandLoader;

use Ilyaotinov\CLI\AbstractCommand;
use Ilyaotinov\CLI\Config\ConfigParserInterface;
use Ilyaotinov\CLI\Config\YamlConfigParser;
use Ilyaotinov\CLI\Input\Input;
use Ilyaotinov\CLI\Output\Output;
use Ilyaotinov\CLI\Output\OutputInterface;
use JetBrains\PhpStorm\NoReturn;
use ReflectionException;

class CommandLoader
{
    public const COMMAND_NAME_INDEX = 1;

    private ConfigParserInterface $configParser;
    private array $argv;

    public function __construct(ConfigParserInterface $configParser, array $argv)
    {
        $this->configParser = $configParser;
        $this->createCommandConfig();
        $this->argv = $argv;
    }

    public function getCommandList(): array
    {
        $commandConfig = $this->getCommandConfig();
        $result = [];

        foreach ($commandConfig as $command) {
            try {
                $this->checkCommandConfig($command);
                $result[] = $this->makeCommandObject($command);
            } catch (\Exception $exception) {
                $this->writeData($exception->getMessage());
                exit();
            }
        }

        return $result;
    }

    public function execute(): void
    {
        $commandNameSet = isset($this->argv[self::COMMAND_NAME_INDEX]);

        if ($commandNameSet) {
            $commandName = $this->argv[self::COMMAND_NAME_INDEX];

            $commandList = $this->getCommandList();
            $commands = array_filter(
                $commandList,
                fn($command) => strtolower($this->argv[self::COMMAND_NAME_INDEX]) === strtolower($command->getName())
            );

            if (count($commands) < 1) {
                $this->writeDataAndExit('Command {{' . $commandName . '}} does not exist');
            }
            $command = $commands[array_key_first($commands)];
            $command->handle();
        } else {
            $this->printCommandList();
        }
    }

    public function printCommandList(): void
    {
        $commandList = $this->getCommandList();
        $this->writeData('Available commands:');
        array_map(function ($command) {
            $this->writeData('-------------------------');
            $this->writeData('-name: ' . $command->getName());
            $this->writeData('-description: ' . $command->getDescription());
            $this->writeData('-------------------------');
        }, $commandList);
    }

    //TODO: replace it
    private function checkCommandConfig(array $commandConf): void
    {
        $classConfNotSet = !isset($commandConf['class']);
        $nameConfNotSet = !isset($commandConf['name']);
        $descriptionConfNotSet = !isset($commandConf['description']);

        if ($classConfNotSet) {
            $this->writeDataAndExit('[Config error] Class command not set in config file');
        }
        if ($nameConfNotSet) {
            $this->writeDataAndExit('[Config error] Name of command not set in config file');
        }
        if ($descriptionConfNotSet) {
            $this->writeDataAndExit('[Config error] Description of command not set in config file');
        }
    }

    #[NoReturn] private function writeDataAndExit(string $data): void
    {
        $output = $this->getOutput();
        $output->writeln($data);
        exit();
    }

    private function writeData(string $data): void
    {
        $output = $this->getOutput();
        $output->writeln($data);
    }

    private function getOutput(): OutputInterface
    {
        return new Output();
    }

    /**
     * @throws ReflectionException
     */
    private function makeCommandObject(array $commandCongif): AbstractCommand
    {
        $class = new \ReflectionClass($commandCongif['class']);
        //TODO: make it polymorphic;
        return $class->newInstance(
            $commandCongif['name'],
            $commandCongif['description'],
            new Input($this->argv),
            new Output()
        );
    }

    private function getCommandConfig(): ?array
    {
        try {
            $commandConfigData = $this->configParser->get('commands');
        } catch (\RuntimeException $exception) {
            $this->writeDataAndExit($exception->getMessage());
        }

        return $commandConfigData;
    }

    //TODO: replace to helper class
    private function createCommandConfig(): void
    {
        $basedir = dirname(__DIR__, YamlConfigParser::BASE_DIR_LEVEL) . '/config';
        if (!is_dir($basedir)) {
            mkdir($basedir);
        }

        $configExist = file_exists($basedir . '/' . 'command.yaml');

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

            $filename = $basedir . '/' . 'command.yaml';
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
