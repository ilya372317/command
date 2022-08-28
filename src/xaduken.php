<?php
use Ilyaotinov\CLI\CommandLoader\CommandLoader;
use Ilyaotinov\CLI\factory\StandardCommandLoaderFactory;

require dirname(__DIR__) . '/vendor/autoload.php';

$commandLoader = new CommandLoader(new StandardCommandLoaderFactory($argv), $argv);

$commandLoader->execute();
