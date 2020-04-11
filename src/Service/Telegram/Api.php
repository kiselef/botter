<?php

namespace App\Service\Telegram;

class Api
{
    const URL = 'https://api.telegram.org/bot%s/%s';

    private $token;
    private $client;

    public function __construct(string $token)
    {
        $this->token = $token;
        $this->client = new \GuzzleHttp\Client();
    }

    public function send(string $method, array $data)
    {
        $this->client->post($this->getMethodUrl($method), [
            'form_params' => $data
        ]);
    }

    public function sendMessage(int $chat_id, string $message)
    {
        $options = [
            'chat_id' => $chat_id,
            'disable_web_page_preview' => true,
        ];
        while (mb_strlen($message) > 4096) {
            $options['text'] = mb_substr($message, 0, 4096);
            $this->send('sendMessage', $options);
            $message = mb_substr($message, 4096);
        }
        $options['text'] = $message;
        $this->send('sendMessage', $options);

    }

    public function sendMessageWithReplyKeyboard(int $chat_id, string $message, array $options)
    {
        $this->send('sendMessage', [
            'chat_id' => $chat_id,
            'text' => $message,

        ]);
    }

    private function getMethodUrl(string $method)
    {
        return sprintf(self::URL, $this->token, $method);
    }
}
