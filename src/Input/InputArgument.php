<?php

namespace Ilyaotinov\CLI\Input;

class InputArgument
{
    private mixed $value;

    public function __construct(mixed $value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return InputArgument
     */
    public function setValue(mixed $value): InputArgument
    {
        $this->value = $value;
        return $this;
    }
}
