<?php

namespace Ilyaotinov\CLI\Input;

class InputOption
{
    private string $name;

    private array $values;

    public function __construct(string $name, array $values)
    {
        $this->name = $name;
        $this->values = $values;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return InputOption
     */
    public function setName(string $name): InputOption
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return array
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @param array $values
     * @return InputOption
     */
    public function setValues(array $values): InputOption
    {
        $this->values = $values;
        return $this;
    }
}
