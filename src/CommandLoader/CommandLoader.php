<?php

namespace Ilyaotinov\CLI\CommandLoader;

use Ilyaotinov\CLI\Config\YamlConfigParser;

class CommandLoader
{
    private function getCommandPath(): string
    {
        $commandConfig = new YamlConfigParser();
        $commandDirRelative = $commandConfig->get('command-directory');
        return dirname(__DIR__, YamlConfigParser::BASE_DIR_LEVEL) . '/' . $commandDirRelative;
    }

    public function getCommandList(): array
    {
        return [$this->getCommandPath()];
    }
}