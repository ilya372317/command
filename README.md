# Simple CLI command library.

## Description:
Library for execute php code by command line. Can parse input argument
and options and write data to Output stream.

## Installation: 
    composer require ilyaotinov/command
## Configuration:

### Run script:

    ../base-app-path/vendor/bin/xaduken

and you will see message like this: 
`You need fill config file. Check it on path: /path/you-project/config/command.yaml`

Library create for you directory and sample config file. It will be contained this data:


    #---
    #commands:
        #  TestCommand:
            #    class: App\AbstractCommand\TestCommand
            #    name: test-command
            #    description: write your commands and put information about them there
            #...

You need to register your own command like in this example.

### TestCommand:
it`s not important setting, you may pass here everything you want. 
### class: 
Fully clarified class name. Used for create command class instance.
### name: 
Name of command. Used in console for execute command.
### description: 
Description of command. You can use {help} argument to retrive itDescription of command. You can use {help} argument to retrieve it.

## Usage:

### Create your own command:

1. Create command class
2. Extend it from Ilyaotinov\CLI\AbstractCommand
3. Define handle method.
4. Register your command in ./config/command.yaml file

And that`s it, you can use you command with ./vendor/bin/xaduken script

### Command class example: 


    <?php

    namespace App\Command;

    use Ilyaotinov\CLI\AbstractCommand;

    class InformationCommand extends AbstractCommand
    {

        public function handle(): void
        {
            $arguments = $this->getInputArguments();
            $options = $this->getInputOptions();

            $this->output->writeln('Arguments: ');
            foreach ($arguments as $argument) {
                $this->output->write('  - ');
                $this->output->writeln($argument->getValue());
            }

            $this->output->writeln('');
            $this->output->writeln('Options: ');
            foreach ($options as $option) {
                $optionValues = $option->getValues();
                $this->output->write('Name: ');
                $this->output->writeln($option->getName());
                $this->output->writeln('Values:');
                foreach ($optionValues as $value) {
                    $this->output->write('  - ');
                    $this->output->writeln($value);
                }
            }
        }
    }

This command print all arguments and options from command like this: 

`$/usr/bin/php ./vendor/bin/xaduken command-info {verbose,overwrite} [log_file=app.log]
{unlimited} [methods={create,update,delete}] [paginate=50] {log}`

Console output will be:

    Arguments: 
      - verbose
      - overwrite
      - unlimited
      - log

    Options:
    Name: log_file
    Values:
      - app.log
    Name: methods
    Values:
      - create
      - update
      - delete
    Name: paginate
    Values:
      - 50


### Command config example:
    commands:
    ...
        InformationCommand:
            class: App\Command\InformationCommand
            name: command-info
            description: print passed arguments and options with values.


## Command execution rules:
Name of command trough by first argument with any format;
- Launch arguments trough in braces separated by commas in next format:
- Single argument: {arg}
- Few arguments: {arg1,arg2,arg3} OR {arg1} {arg2} {arg3}
  OR {arg1,arg2} {arg3}
- Launch options trough in square braces in next format:
- Option with single value: [name=value]
- Option with few values: [name={value1,value2,value3}]

## Command input and output:
### Input:
for get arguments, use code like this in command class:

    $arguments = $this->getInputArguments();
This method will be return list of InputArgument objects.
Input argument has one method `InputArgument::getValue()`, which 
return argument value in string format.

Fore get options you need use this code in command class:

    $options = $this->getInputOptions();
This method return list of InputOption objects.
InputOption class has two methods:
1. `InputOption::getName()` - return name of option in string format
2. `InputOption::getValues()` - return list of strings, with option values

### Output
For write something in console you can use Output object. 
Output object is implementation of OutputInterface interface.

This interface contains two methods:
1. `OutputInterface::write(string $data)` - write data to console in the same line - write data to console in the same line.
2. `OutputInterface::writeln(string $data)` - write data to console with new line.

## Help: 
For get description of the command, use {help argument}
### Example:
`$/usr/bin/php ./vendor/bin/xaduken command-info {help}`

## Command list
You can print command list by run xaduken script without command name.
### Example:
`$/usr/bin/php ./vendor/bin/xaduken`

You see something like this:

    Available commands:
    -------------------------
    -name: command-info
    -description: print passed arguments and options with values.
    -------------------------
