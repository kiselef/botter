<?php

if ($argc > 1) {

    $args = [];
    for ($i = 1; $i < $argc; $i++) {
        $command_params = explode('=', $argv[$i]);
        $args[$command_params[0]] = $command_params[1];
    }

    execute($args['name']);

}

function execute(string $command_name)
{
    require 'vendor/autoload.php';

    $config = require 'config.php';

    $command = \App\Service\Handler\Factory\CommandFactory::create($command_name);
    $command->execute();

    $token = $config['telegram']['token'];
    $chat_id = $config['telegram']['default_chat_id'];

    $telegram = new \App\Service\Telegram\Api($token);
    $telegram->sendMessage($chat_id, $command->getResult());
}




