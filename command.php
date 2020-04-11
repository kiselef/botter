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

    /* @var \App\Service\Handler\Command\VkCommand $command */
    $command = \App\Service\Handler\Factory\CommandFactory::create($command_name);
    $command->execute();

    $token = $config['telegram']['token'];
    $chat_id = $config['telegram']['default_chat_id'];

    $telegram = new \App\Service\Telegram\Api($token);

    if ($data = $command->getResult()) {
        if (count($data['result']) === 1
            && isset($data['result'][0]['attachment'])
            && $data['result'][0]['attachment']['type'] === 'photo'
        ) {
            $text = $data['title'] . $data['result'][0]['text'];
            $photo_url =  $data['result'][0]['attachment']['url'];
            $telegram->sendPhoto($chat_id, $photo_url, $text);
        } else {
            $text = $data['title'];
            foreach ($data['result'] as $result) {
                $text .= $result['text'];
            }
            $telegram->sendMessage($chat_id, $text);
        }
    }

    echo json_encode('OK');
}




