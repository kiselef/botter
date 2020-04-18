<?php

namespace App\Service\Handler\Command;

use App\Service\Handler\VKApi;

/**
 * Class VkCommand
 * @package App\Service\Handler\Command
 */
abstract class VkCommand extends BaseCommand
{
    protected $vk;

    public function __construct(array $args = [])
    {
        parent::__construct($args);

        $token = $this->getVKSettings()['token'];
        $this->vk = new VKApi($token);
    }

    private function getVKSettings()
    {
        $config = include __DIR__ . '/../config.php';
        return $config['vk'];
    }

    protected function getLastDatePostByOwnerId(int $owner_id)
    {
        $filename = __DIR__ . '/../log/command/histories/' . $this->getName() . $owner_id;

        return file_exists($filename) ? intval(file_get_contents($filename)) : 0;
    }

    protected function setLastDatePostByOwnerId(int $owner_id, int $timestamp)
    {
        $filename = __DIR__ . '/../log/command/histories/' . $this->getName() . $owner_id;

        return file_put_contents($filename, $timestamp);
    }
}
