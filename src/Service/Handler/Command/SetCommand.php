<?php

namespace App\Service\Handler\Command;

use App\Service\Telegram\Message\PlainMessage;
use App\Service\Telegram\Sender;

class SetCommand extends VkCommand
{
    public function execute() : void
    {
        if ($this->args) {
            $response = $this->vk->groupsGetByIds($this->args);

            $groups = array_column($response, 'name', 'id');
            $this->setLastDatePostByOwnerId(100, json_encode($groups));

            $sender = new Sender($this->api);
            $message = new PlainMessage(['text' => 'Настройки успешно установлены. Далее используйте команду "/get".']);
            $sender->send($this->chat_id, $message);
        }
    }
}
