<?php
require dirname(__DIR__) . '/vendor/autoload.php';

$commandLoader = new \Ilyaotinov\CLI\CommandLoader\CommandLoader();

var_dump($commandLoader->getCommandList());