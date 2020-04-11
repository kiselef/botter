<?php

use App\Service\Handler\Factory\CommandFactory;
use App\Service\Telegram\Api;

require '../vendor/autoload.php';

$config = require '../config.php';

$path = $_SERVER['PATH_INFO'] ?? '';
if ($path === '/hook') {

    $request = \App\SimpleRequest::instance();
    if ($request->isPost()
        && $request_data = json_decode($request->postRaw(), true)
    ) {
        $user_command = $request_data['channel_post']['text'];

        $command = CommandFactory::create($user_command);
        $command->execute();

        $token = $config['telegram']['token'];
        $chat_id = $config['telegram']['default_chat_id'];

        $telegram = new Api($token);
        $telegram->sendMessage($chat_id, $command->getResult());
    }

    echo json_encode('OK');
}
