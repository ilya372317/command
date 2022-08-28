<?php

namespace Ilyaotinov\CLI;

use Ilyaotinov\CLI\Input\InputArgument;
use Ilyaotinov\CLI\Input\InputDefinition;
use Ilyaotinov\CLI\Input\InputInterface;
use Ilyaotinov\CLI\Input\InputOption;
use Ilyaotinov\CLI\Output\OutputInterface;

/**
 * Base command class
 *
 * @author Ilya Otinov
 */
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

    protected function getInputDefinition(): InputDefinition
    {
        return $this->input->getDefinition();
    }

    /**
     * @return InputOption[]
     */
    protected function getInputOptions(): array
    {
        return $this->getInputDefinition()->getOptions();
    }

    /**
     * @return InputArgument[]
     */
    protected function getInputArguments(): array
    {
        return $this->getInputDefinition()->getArguments();
    }

    public function hasHelpArgument(): bool
    {
        $inputDefinition = $this->getInputDefinition();
        return $inputDefinition->hasArgument('help');
    }

    abstract public function handle(): void;
}
