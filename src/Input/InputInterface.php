<?php

namespace Ilyaotinov\CLI\Input;

interface InputInterface
{
    public function getDefinition(): InputDefinition;
}
