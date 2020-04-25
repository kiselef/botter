<?php

namespace App\Service\Telegram;

class Response
{
    private $is_success;
    private $content = [];

    public function __construct(?array $telegram_response)
    {
        $this->is_success = $telegram_response['ok'] ?? false;

        $this->content['date'] = empty($telegram_response) ? time() : $telegram_response['result']['date'] ?? time();
    }

    public function isSuccess(): bool
    {
        return $this->is_success;
    }

    public function getContent(): array
    {
        return $this->content;
    }
}
