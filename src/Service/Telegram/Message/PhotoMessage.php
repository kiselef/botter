<?php

namespace App\Service\Telegram\Message;

class PhotoMessage extends TelegramMessage
{
    protected $text_field_name = 'caption';

    public function getMethod(): string
    {
        return 'sendPhoto';
    }

    public function required(): array
    {
        return ['photo'];
    }
}
