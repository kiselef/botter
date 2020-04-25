<?php

namespace App\Service\Handler\Factory;

use App\Service\Handler\Command\BaseCommand;

class CommandFactory
{
    public static function create(string $command) : BaseCommand
    {
        $command_args = explode(' ', preg_replace('/[\s\t]+/', ' ', $command));

        $command_name = ucfirst(array_shift($command_args));
        $commandClassName = "App\\Service\\Handler\\Command\\{$command_name}Command";
        if (! class_exists($commandClassName)) {
            throw new \InvalidArgumentException("Command $command_name is undefined.");
        }

        /* @var \App\Service\Handler\Command\BaseCommand $command */
        $command = new $commandClassName($command_args);

        return $command;
    }
}
