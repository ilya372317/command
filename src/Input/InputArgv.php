<?php

namespace Ilyaotinov\CLI\Input;

class InputArgv implements InputInterface
{
    private InputDefinition $inputDefinition;

    public function __construct(array $argv)
    {
        $this->inputDefinition = $this->parseInputDefinition($argv);
    }

    /**
     * @inheritDoc
     */
    public function getDefinition(): InputDefinition
    {
        return $this->inputDefinition;
    }

    private function parseInputDefinition(array $argv): InputDefinition
    {
        return InputDefinitionParser::parse($argv);
    }
}