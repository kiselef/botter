<?php

namespace App\Service\VK;

use VK\Client\VKApiClient;

class Api
{
    private $client;
    private $token;

    public function __construct(string $token)
    {
        $this->client = new VKApiClient();
        $this->token = $token;
    }

    public function wall(int $owner_id, int $limit = 1)
    {
        return $this->client->wall()->get($this->token, [
            'owner_id' => -$owner_id,
            'count' => $limit,
            'extended' => 1,
        ]);
    }

    public function groupsGetByIds(array $ids)
    {
        return $this->client->groups()->getById($this->token, [
            'group_ids' => $ids
        ]);
    }
}
