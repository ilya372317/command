<?php
require dirname(__DIR__) . '/vendor/autoload.php';

$commandLoader = new \Ilyaotinov\CLI\CommandLoader\CommandLoader(new \Ilyaotinov\CLI\Config\YamlConfigParser());
$commandList = $commandLoader->getCommandList();

foreach ($commandList as $command) {
    $command->handle();
}
