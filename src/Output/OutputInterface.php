<?php

namespace Ilyaotinov\CLI\Output;

interface OutputInterface
{
    /**
     * Write data to output stream.
     *
     * @param string $data
     * @return void
     */
    public function write(string $data): void;

    /**
     * Write data to output stream from new line.
     *
     * @param string $data
     * @return void
     */
    public function writeln(string $data): void;
}
