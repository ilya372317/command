<?php

namespace Ilyaotinov\CLI\CommandLoader;

use Ilyaotinov\CLI\AbstractCommand;
use Ilyaotinov\CLI\factory\CommandLoaderFactoryInterface;
use Ilyaotinov\CLI\Input\InputInterface;
use Ilyaotinov\CLI\Output\OutputInterface;
use JetBrains\PhpStorm\NoReturn;
use ReflectionException;

class CommandLoader
{
    public const COMMAND_NAME_INDEX = 1;

    private CommandLoaderFactoryInterface $commandLoaderFactory;
    private array $argv;

    public function __construct(CommandLoaderFactoryInterface $commandLoaderFactory, array $argv)
    {
        $this->commandLoaderFactory = $commandLoaderFactory;
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
            $this->doExecute($command);
        } else {
            $this->printCommandList();
        }
    }

    private function doExecute(AbstractCommand $command): void
    {
        if ($command->hasHelpArgument()) {
            $this->writeDataAndExit($command->getDescription());
        }
        $command->handle();
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
        return $this->commandLoaderFactory->getOutput();
    }

    private function getInput(): InputInterface
    {
        return $this->commandLoaderFactory->getInput();
    }

    /**
     * @throws ReflectionException
     */
    private function makeCommandObject(array $commandConfig): AbstractCommand
    {
        $class = new \ReflectionClass($commandConfig['class']);
        //TODO: make it polymorphic;
        return $class->newInstance(
            $commandConfig['name'],
            $commandConfig['description'],
            $this->getInput(),
            $this->getOutput()
        );
    }

    private function getCommandConfig(): ?array
    {
        $configParser = $this->commandLoaderFactory->getConfigParser();
        try {
            $commandConfigData = $configParser->get('commands');
        } catch (\RuntimeException $exception) {
            $this->writeDataAndExit($exception->getMessage());
        }

        return $commandConfigData;
    }
}
