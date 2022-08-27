<?php

use Ilyaotinov\CLI\CommandLoader\CommandLoader;
use Ilyaotinov\CLI\Config\YamlConfigParser;

require dirname(__DIR__) . '/vendor/autoload.php';

$commandLoader = new CommandLoader(new YamlConfigParser(), $argv);

$commandLoader->execute();
