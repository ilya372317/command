<?php

namespace Ilyaotinov\CLI\factory;

use Ilyaotinov\CLI\Config\ConfigParserInterface;
use Ilyaotinov\CLI\Config\YamlConfigParser;
use Ilyaotinov\CLI\Input\InputArgv;
use Ilyaotinov\CLI\Input\InputInterface;
use Ilyaotinov\CLI\Output\Output;
use Ilyaotinov\CLI\Output\OutputInterface;

class StandardCommandLoaderFactory implements CommandLoaderFactoryInterface
{
    private array $argv;

    public function __construct(array $argv)
    {
        $this->argv = $argv;
    }

    public function getOutput(): OutputInterface
    {
        return new Output();
    }

    public function getInput(): InputInterface
    {
        return new InputArgv($this->argv);
    }

    public function getConfigParser(): ConfigParserInterface
    {
        return new YamlConfigParser();
    }
}