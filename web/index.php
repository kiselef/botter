<?php

use App\Service\Handler\Factory\CommandFactory;
use App\Service\Telegram\Api;

require '../vendor/autoload.php';

$config = require '../config.php';

$path = $_SERVER['REQUEST_URI'] ?? '';
if (strpos($path, '/hook') !== false) {

    $request = \App\SimpleRequest::instance();
    if ($request->isPost()
        && $request_data = json_decode($request->postRaw(), true)
    ) {
        if ($request['entities']['type'] !== 'bot_command') {
            exit;
        }

        $user_command = $request_data['message']['text'];
        $chat_id = $request_data['chat']['id'];

        $token = $config['telegram']['token'];
        $chat_id = $config['telegram']['default_chat_id'];

        $telegram = new Api($token);

        $command = CommandFactory::create($user_command);
        $command->setApi($telegram);
        $command->setChatId($chat_id);

        $command->execute();
    }
}
