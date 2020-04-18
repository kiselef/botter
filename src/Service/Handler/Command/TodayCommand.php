<?php

namespace App\Service\Handler\Command;

use App\Service\Handler\Factory\FromVkPostTelegramMessageFactory;
use App\Service\Handler\VkPost;
use App\Service\Telegram\Sender;

class TodayCommand extends VkCommand
{
    const
        DEFAULT_POST_NUMBER = 10,
        DEFAULT_GROUP_ID = 27838907;

    public function execute() : void
    {
        if ($this->args) {
            $owner_id = $this->args[0];
            $limit = $this->args[1] ?? self::DEFAULT_POST_NUMBER;
            $response = $this->vk->wall($owner_id, $limit);
            //$group_name = $response['groups'] ? $response['groups'][0]['name'] : 'No Name';
            $posts = $this->getNewPosts($response);

            $sender = new Sender($this->api);
            /* @var \App\Service\Handler\VkPost $post */
            foreach ($posts as $post) {
                try {
                    $message = FromVkPostTelegramMessageFactory::create($post);
                    if ($sender->send($this->chat_id, $message) !== false) {
                        $last_success_post = $post;
                    }
                } catch (\Exception $e) {
                    // log
                }
            }

            if (isset($last_success_post)) {
                $this->setLastDatePostByOwnerId($owner_id, $last_success_post->date);
            }
        }
    }

    private function getLastDatePostByOwnerId(int $owner_id)
    {
        $filename = __DIR__ . '/../log/command/histories/today-' . $owner_id;

        return file_exists($filename) ? intval(file_get_contents($filename)) : 0;
    }

    private function setLastDatePostByOwnerId(int $owner_id, int $timestamp)
    {
        $filename = __DIR__ . '/../log/command/histories/today-' . $owner_id;

        return file_put_contents($filename, $timestamp);
    }

    private function getNewPosts(array $info = [])
    {
        $result = [];
        $date = $this->getLastDatePostByOwnerId($info['groups'][0]['id']);
        foreach ($info['items'] as $item) {
            if ($item['date'] <= $date) {
                continue;
            }
            $result[] = new VkPost($item);
        }

        usort($result, function($a, $b) {
            return $a->date > $b->date ? 1 : -1;
        });

        return $result;
    }

    public function getResult()
    {
        return $this->result;
    }
}
