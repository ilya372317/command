<?php

namespace Ilyaotinov\CLI\factory;

use Ilyaotinov\CLI\Config\ConfigParserInterface;
use Ilyaotinov\CLI\Input\InputInterface;
use Ilyaotinov\CLI\Output\OutputInterface;

interface CommandLoaderFactoryInterface
{
    /**
     * Get output stream for passing data to the console.
     *
     * @return OutputInterface
     */
    public function getOutput(): OutputInterface;

    /**
     * Get the class for manage data from Input stream.
     *
     * @return InputInterface
     */
    public function getInput(): InputInterface;

    /**
     *  Get parser, to get information about command from config file.
     *
     * @return ConfigParserInterface
     */
    public function getConfigParser(): ConfigParserInterface;

    /**
     * Get arguments and options, which was passed with command from console.
     *
     * @return string[]
     */
    public function getCommandParameters(): array;
}