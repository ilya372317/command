<?php

namespace Ilyaotinov\CLI;

use Ilyaotinov\CLI\Input\InputInterface;
use Ilyaotinov\CLI\Output\OutputInterface;

abstract class AbstractCommand
{
    protected string $name;
    protected string $description;
    protected InputInterface $input;
    protected OutputInterface $output;

    public function __construct(string $name, string $description, InputInterface $input, OutputInterface $output)
    {
        $this->name = $name;
        $this->description = $description;
        $this->input = $input;
        $this->output = $output;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}