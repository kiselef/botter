<?php

namespace App\Service\Handler\Command;

use App\Service\Handler\Result\BaseResult;
use App\Service\Telegram\Api;

/**
 * @property Api $api
 * @property array      $option
 * @package App\Service\Bot\Command
 */
abstract class BaseCommand
{
    protected $api;
    protected $chat_id;
    protected $args = [];
    protected $options = [];

    abstract public function execute() : void;

    public function __construct(array $args = [])
    {
        $this->args = $args;

    }

    public function getArgs() : array
    {
        return $this->args;
    }

    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    public function setApi(Api $api)
    {
        $this->api = $api;
    }

    public function setChatId(int $chat_id)
    {
        $this->chat_id = $chat_id;
    }
}
