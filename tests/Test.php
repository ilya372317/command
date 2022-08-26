<?php

use Ilyaotinov\CLI\CommandLoader\CommandLoader;
use Ilyaotinov\CLI\Config\YamlConfigParser;

require dirname(__DIR__) . '/vendor/autoload.php';

$commandLoader = new CommandLoader(new YamlConfigParser());
$commandList = $commandLoader->getCommandList();

foreach ($commandList as $command) {
    $command->handle();
}
