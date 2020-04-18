<?php

namespace App\Service\Telegram\Message;

class PlainMessage extends TelegramMessage
{
    public function getMethod()
    {
        return 'sendMessage';
    }

    public function getText()
    {
        return $this->options['text'] ?? '';
    }

    public function required()
    {
        return ['text'];
    }
}
