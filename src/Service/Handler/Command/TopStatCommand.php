<?php

namespace App\Service\Handler\Command;

use App\Service\Handler\View;
use App\Service\Handler\VkPost;
use App\Service\Telegram\Message\PlainMessage;
use App\Service\Telegram\Sender;

class TopStatCommand extends VkCommand
{
    const
        DEFAULT_TOP_LIMIT   = 5,
        DEFAULT_POST_NUMBER = 20;

    private $default_date_range = [
        'yesterday',
        'today',
    ];

    private $default_group_ids = [
        60246922,
        63677604,
        125528525,
        27838907,
        5421782,
        108494404,
    ];

    public function execute(): void
    {
        $posts = $this->getTopPosts();

        $sender = new Sender($this->api);
        try {
            $message = new PlainMessage(['text' => View::result('top_stat', compact('posts'))]);
            $response = $sender->send($this->chat_id, $message);
            if (!$response->isSuccess()) {
                $this->addWarning('Message was not sent.');
            }
        } catch (\Exception $e) {
            $this->addWarning($e->getMessage());
        }
    }

    private function getAllPosts(): array
    {
        $owner_ids = $this->getGroupIds();

        $posts = [];
        foreach ($owner_ids as $owner_id) {
            $response = $this->vk->wall($owner_id, self::DEFAULT_POST_NUMBER);
            $posts = array_merge($posts, $this->getPostsFilteredByDate($response));
        }

        return $posts;
    }

    private function getTopPosts(int $limit = self::DEFAULT_TOP_LIMIT)
    {
        $posts = $this->getAllPosts();
        usort($posts, function (VkPost $a, VkPost $b) {
            return $b->getPopularity() <=> $a->getPopularity();
        });

        return array_slice($posts, 0, $limit);
    }

    private function getGroupIds(): array
    {
        $owner_ids = isset($this->args[0]) ? explode(',', $this->args[0]) : $this->default_group_ids;
        $owner_ids = array_filter($owner_ids, function ($id) {
            if (is_numeric($id)) {
                return true;
            }
            $this->addWarning(sprintf('Onwer Id %s is not numeric type.'));
            return false;
        });

        return $owner_ids;
    }

    private function getPostsFilteredByDate(array $vk_response = []): array
    {
        [$from, $to] = $this->getDateRange();

        $result = [];
        foreach ($vk_response['items'] as $item) {
            if ($item['date'] < $from || $item['date'] >= $to) {
                continue;
            }
            $result[] = new VkPost(array_merge($item, [
                'screen_name' => $vk_response['groups'][0]['screen_name']
            ]));
        }

        return $result;
    }

    private function getDateRange(): array
    {
        [$from, $to] = isset($this->args[1]) ? explode(',', $this->args[1]) : $this->default_date_range;

        $from = is_numeric($from) ? $from : strtotime($from);
        $to = is_numeric($to) ? $to : strtotime($to);

        // TODO: validate values

        return [$from, $to];
    }
}
