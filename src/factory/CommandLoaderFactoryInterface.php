<?php

namespace Ilyaotinov\CLI\factory;

use Ilyaotinov\CLI\Config\ConfigParserInterface;
use Ilyaotinov\CLI\Input\InputInterface;
use Ilyaotinov\CLI\Output\OutputInterface;

interface CommandLoaderFactoryInterface
{
    public function getOutput(): OutputInterface;

    public function getInput(): InputInterface;

    public function getConfigParser(): ConfigParserInterface;
}