<?php

namespace App\Service\Handler\Factory;

use App\Service\Handler\View;
use App\Service\Telegram\Message\PhotoMessage;
use App\Service\Telegram\Message\PlainMessage;
use App\Service\Telegram\Message\TelegramMessage;
use App\Service\VK\Post as VKPost;

class TelegramMessageFactory
{
    public static function createFromVKPost(VKPost $post, string $template = 'default_text_message'): TelegramMessage
    {
        if (empty(trim($post->text)) && !$post->isPhoto()) {
            throw new \Exception('Empty post: ' . $post->id);
        }

        $text = $template ? View::result($template, compact('post')) : $post->text;

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
