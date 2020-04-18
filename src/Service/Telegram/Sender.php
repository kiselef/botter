<?php

namespace App\Service\Telegram;

use App\Service\Telegram\Message\TelegramMessage;

class Sender
{
    private $api;

    public function __construct(Api $api)
    {
        $this->api = $api;
    }

    public function send(int $chat_id, TelegramMessage $message)
    {
        if (! $message->isValid()) {
            return false;
        }

        $options = $message->getOptions();
        $options['chat_id'] = $chat_id;

        return $this->api->send($message->getMethod(), $options);
    }
}
