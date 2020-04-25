<?php

namespace App\Service\Handler\Factory;

use App\Service\Handler\VkPost;
use App\Service\Telegram\Message\PhotoMessage;
use App\Service\Telegram\Message\PlainMessage;
use App\Service\Telegram\Message\TelegramMessage;

class FromVkPostTelegramMessageFactory
{
    public static function create(VkPost $post): TelegramMessage
    {
        $text = $post->text . PHP_EOL . $post->link_vk;

        if ($post->isPhoto()) {
            $message = new PhotoMessage([
                'caption' => $text,
                'photo' => $post->attach['url'],
            ]);
        } else {
            $message = new PlainMessage([
                'text' => $text,
            ]);
        }

        if ($message->isValid() === false) {
            throw new \InvalidArgumentException('Required options can not be empty.');
        }

        return $message;
    }
}
