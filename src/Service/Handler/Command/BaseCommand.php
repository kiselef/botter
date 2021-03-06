<?php

namespace App\Service\Handler\Command;

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
    protected $warnings = [];

    abstract public function execute() : void;

    public function __construct(array $args = [])
    {
        $this->args = $args;

    }

    public function getArgs() : array
    {
        return $this->args;
    }

    public function setOptions(array $options) : void
    {
        $this->options = $options;
    }

    public function setApi(Api $api) : void
    {
        $this->api = $api;
    }

    public function setChatId(int $chat_id) : void
    {
        $this->chat_id = $chat_id;
    }

    public function getName()
    {
        preg_match('/\\\\(\w+)Command/', static::class, $matches);
        return $matches[1];
    }

    protected function addWarning(string $message)
    {
        $this->warnings[] = trim($message);
    }

    public function getWarnings()
    {
        return $this->warnings;
    }

    public function getCommandCacheDir()
    {
        return __DIR__ . '/../command_cache';
    }

    protected function getCommandCache($owner_id)
    {
        $filename = $this->getCacheFilename($owner_id);

        return file_exists($filename) ? intval(file_get_contents($filename)) : 0;
    }

    protected function setCommandCache($owner_id, $timestamp)
    {
        return file_put_contents($this->getCacheFilename($owner_id), $timestamp);
    }

    private function getCacheFilename($id): string
    {
        return $this->getCommandCacheDir() . $this->getName() . $id;
    }
}
