<?php

namespace Ilyaotinov\CLI\Output;

class Output implements OutputInterface
{
    /**
     * @inheritDoc
     */
    public function write(string $data): void
    {
        $this->doWrite($data);
    }

    /**
     * @inheritDoc
     */
    public function writeln(string $data): void
    {
        $data = $data . PHP_EOL;
        $this->write($data);
    }

    private function doWrite(string $data): void
    {
        fwrite(STDOUT, $data);
    }
}
