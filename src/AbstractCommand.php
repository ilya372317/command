<?php

namespace Ilyaotinov\CLI;

use Ilyaotinov\CLI\Input\InputInterface;
use Ilyaotinov\Output\OutputInterface;

abstract class AbstractCommand
{
    private InputInterface $input;
    private OutputInterface $output;
}