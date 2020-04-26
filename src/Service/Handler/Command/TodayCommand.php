<?php

namespace App\Service\Handler\Command;

use App\Service\Handler\Factory\TelegramMessageFactory;
use App\Service\VK\Post as VKPost;
use App\Service\Telegram\Sender;

class TodayCommand extends VkCommand
{
    const
        DEFAULT_POST_NUMBER = 5,
        DEFAULT_GROUP_ID = 27838907;

    public function execute(): void
    {

        $text = 'Рисунок [id82459560|Татьяны Морышковой]';
        $text = preg_replace('/\[(id\d+)\|(.+)\]/', '<a href="$1">$2</a>', $text);
        var_dump($text); exit;

        if ($this->args) {
            $owner_id = $this->args[0];
            $limit = $this->args[1] ?? self::DEFAULT_POST_NUMBER;
            $response = $this->vk->wall($owner_id, $limit);
            $posts = $this->getNewPosts($response);

            $sender = new Sender($this->api);
            /* @var VKPost $post */
            foreach ($posts as $post) {
                try {
                    $message = TelegramMessageFactory::createFromVKPost($post);
                    if ($sender->send($this->chat_id, $message) !== false) {
                        $last_success_post = $post;
                    }
                } catch (\Exception $e) {
                    $this->addWarning($e->getMessage());
                }
            }

            if (isset($last_success_post)) {
                $this->setCommandCache($owner_id, $last_success_post->date);
            }
        }
    }

    private function getNewPosts(array $info = [])
    {
        $result = [];
        $date = $this->getCommandCache($info['groups'][0]['id']);
        foreach ($info['items'] as $item) {
            if ($item['date'] <= $date) {
                continue;
            }
            $result[] = new VKPost($item);
        }

        usort($result, function($a, $b) {
            return $a->date > $b->date ? 1 : -1;
        });

        return $result;
    }
}
