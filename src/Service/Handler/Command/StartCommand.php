<?php

namespace App\Service\Handler\Command;

use App\Service\Handler\View;
use App\Service\Telegram\Message\PlainMessage;
use App\Service\Telegram\Sender;

class StartCommand extends BaseCommand
{
    public function execute(): void
    {
        (new Sender($this->api))->send($this->chat_id, new PlainMessage([
                'text' => View::result('start'),
            ])
        );
    }
}
