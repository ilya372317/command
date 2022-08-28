<?php

namespace Ilyaotinov\CLI\Input;

class InputDefinition
{
    private array $options;
    private array $arguments;

    public function __construct(array $arguments = [], array $options = [])
    {
        $this->arguments = $arguments;
        $this->options = $options;
    }

    /**
     * @return InputOption[]
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @return InputArgument[]
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    public function setOptions(array $options): self
    {
        $this->options = $options;
        return $this;
    }

    public function addOption(InputOption $inputOption): self
    {
        $this->options[] = $inputOption;
        return $this;
    }

    public function setArguments(array $arguments): self
    {
        $this->arguments = $arguments;
        return $this;
    }

    public function addArgument(InputArgument $inputArgument): self
    {
        $this->arguments[] = $inputArgument;
        return $this;
    }

    public function hasArgument(string $argumentValue): bool
    {
        $needleArgument = array_filter($this->arguments, function (InputArgument $argument) use ($argumentValue) {
            return $argument->getValue() === $argumentValue;
        });

        return count($needleArgument) > 0;
    }

    public function hasOption(string $optionName): bool
    {
        $needleOption = array_filter($this->options, function (InputOption $option) use ($optionName) {
            return $option->getName() === $optionName;
        });

        return count($needleOption) > 0;
    }
}
