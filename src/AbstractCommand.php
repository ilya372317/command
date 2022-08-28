<?php

namespace Ilyaotinov\CLI;

use Ilyaotinov\CLI\Input\InputArgument;
use Ilyaotinov\CLI\Input\InputDefinition;
use Ilyaotinov\CLI\Input\InputInterface;
use Ilyaotinov\CLI\Input\InputOption;
use Ilyaotinov\CLI\Output\OutputInterface;

/**
 * Base class for every command.
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

    /**
     * Check if arguments list has a help option.
     *
     * @return bool
     */
    public function hasHelpArgument(): bool
    {
        $inputDefinition = $this->getInputDefinition();
        return $inputDefinition->hasArgument('help');
    }

    /**
     * Business logic of command.
     *
     * @return void
     */
    abstract public function handle(): void;
}
