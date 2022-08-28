<?php

namespace Ilyaotinov\CLI\Input;

class InputDefinitionParser
{
    public static function parse(array $inputData): InputDefinition
    {
        array_shift($inputData);
        array_shift($inputData);
        $optionsArray = [];
        $optionsObjects = [];
        $argumentsObjects = [];

        array_map(function ($input) use (&$argumentsObjects, &$optionsArray) {
            $input = trim($input);
            $isArgument = preg_match('/^(?!(\[)).+(?!(]))$/i', $input);

            $isOption = preg_match('/^\[.+(=).+]$/i', $input);

            // If input is argument
            if ($isArgument) {
                $input = trim($input, '{}');
                $isMultiArgument = preg_match('/^.+(,).+$/i', $input);
                $argumentsObjects[] = new InputArgument(trim($input, ','));
            }
            // If input is option
            if ($isOption) {
                $input = trim($input, '[]');
                $optionName = preg_replace('/=.+/i', '', $input);
                $optionValue = preg_replace('/^.+=/i', '', $input);

                $optionsArray[$optionName][] = $optionValue;
            }
        },
            $inputData);
        foreach ($optionsArray as $optionName => $optionValues) {
            $optionsObjects[] = new InputOption($optionName, $optionValues);
        }

        return new InputDefinition($argumentsObjects, $optionsObjects);
    }
}
