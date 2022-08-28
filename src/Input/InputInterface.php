<?php

namespace Ilyaotinov\CLI\Input;

interface InputInterface
{
    /**
     * Get input definition.
     *
     * @return InputDefinition
     */
    public function getDefinition(): InputDefinition;
}
