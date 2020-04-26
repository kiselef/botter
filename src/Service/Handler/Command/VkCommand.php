<?php

namespace App\Service\Handler\Command;

use App\Service\VK\Api;

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
        $this->vk = new Api($token);
    }

    private function getVKSettings()
    {
        $config = include __DIR__ . '/../config.php';
        return $config['vk'];
    }
}
