<?php

namespace Ilyaotinov\CLI\Helper;

use Ilyaotinov\CLI\Config\YamlConfigParser;

class ConfigHelper
{
    public static function createCommandConfig(): void
    {
        $basedir = dirname(__DIR__, YamlConfigParser::BASE_DIR_LEVEL) . '/config';
        if (!is_dir($basedir)) {
            mkdir($basedir);
        }

        $configExist = file_exists($basedir . '/' . 'command.yaml');

        if (!$configExist) {
            $yamlData = yaml_emit([
                'commands' => [
                    "TestCommand" => [
                        "class" => 'App\\AbstractCommand\\TestCommand',
                        "name" => 'test-command',
                        "description" => 'write your commands and put information about them there'
                    ]
                ]
            ], YAML_UTF8_ENCODING);

            $filename = $basedir . '/' . 'command.yaml';
            $file = new \SplFileObject($filename, 'w+', '');
            $file->fwrite($yamlData);
            $file->rewind();
            $file->fflush();

            self::writeDataOnEveryLineInFile($filename, '#');
        }
    }

    public static function writeDataOnEveryLineInFile(string $filename, string $data): void
    {
        $originalFilename = $filename;

        $originalFileObject = new \SplFileObject($originalFilename);

        $tempFilename = tempnam(sys_get_temp_dir(), 'php_prepend_');

        while (!$originalFileObject->eof()) {
            $currentOriginalFileString = $originalFileObject->fgets();
            if (strlen(trim($currentOriginalFileString)) > 0) {
                file_put_contents($tempFilename, $data, FILE_APPEND);
            }

            file_put_contents($tempFilename, $currentOriginalFileString, FILE_APPEND);
            $originalFileObject->next();
        }
        unlink($originalFilename);
        rename($tempFilename, $originalFilename);
    }
}
