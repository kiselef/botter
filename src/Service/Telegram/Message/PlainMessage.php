<?php

namespace App\Service\Telegram\Message;

class PlainMessage extends TelegramMessage
{
    public function getMethod(): string
    {
        return 'sendMessage';
    }

    public function required(): array
    {
        return ['text'];
    }
}
