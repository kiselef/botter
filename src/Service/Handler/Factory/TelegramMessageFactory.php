<?php

namespace App\Service\Handler\Factory;

use App\Service\Handler\VkPost;
use App\Service\Telegram\Message\PlainMessage;
use App\Service\Telegram\Message\TelegramMessage;

class TelegramMessageFactory
{
    public static function createPlainMessageFromVKPost(VkPost $post): TelegramMessage
    {
        $message = new PlainMessage([
            'text' => $post->text,
        ]);

        if ($message->isValid() === false) {
            throw new \InvalidArgumentException('Required options can not be empty.');
        }

        return $message;
    }
}
