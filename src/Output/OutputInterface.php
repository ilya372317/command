<?php

namespace Ilyaotinov\CLI\Output;

interface OutputInterface
{
    public function write(string $data): void;

    public function writeln(string $data): void;
}
