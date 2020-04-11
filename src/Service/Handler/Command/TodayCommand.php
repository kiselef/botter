<?php

namespace App\Service\Handler\Command;

use VK\Client\VKApiClient;
use VK\OAuth\Scopes\VKOAuthUserScope;
use VK\OAuth\VKOAuth;
use VK\OAuth\VKOAuthDisplay;
use VK\OAuth\VKOAuthResponseType;

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

            $this->result = $this->getNewPosts($response);
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
            $content_info = [
                'content' => $item['text'],
                'comments' => $item['comments']['count'],
                'likes' => $item['comments']['count'],
                'link_vk' => 'https://vk.com/wall-' . abs($item['owner_id']) . '_' . $item['id'],
            ];
            if (isset($item['attachments'])) {
                $attaches = $item['attachments'];
                foreach ($attaches as $attach) {
                    // TODO: use $attach['type'] (link, photo)
                    if (isset($attach['link'])) {
                        $content_info['link_source'] = $attach['link']['url'];
                    }
                    if (isset($attach['photo'])) {
                        $attachment = [
                            'type' => 'photo',
                            'url' => array_pop($attach['photo']['sizes'])['url']
                        ];
                    }
                }

            }

            $text = "\n{$content_info['content']}\n{$content_info['link_vk']}";
            if (isset($content_info['link_source'])) {
                $text .= PHP_EOL . $content_info['link_source'] . PHP_EOL;
            }

            if ($content_info['comments'] > 15) {
                $text .= "\nКомментариев: {$content_info['comments']}";
            }

            $r = compact('text');
            if (isset($attachment)) {
                $r['attachment'] = $attachment;
            }
            $result[] = $r;

        }

        if ($result) {
            $group_name = $info['groups'] ? $info['groups'][0]['name'] : 'No Name';
            $item = array_shift($info['items']);
            $title = "<$group_name>";
            $this->setLastDatePostByOwnerId(abs($item['owner_id']), $item['date']);

            return array_merge(compact('title'), compact('result'));
        }

        return [];
    }

    public function getResult()
    {
        return $this->result;
    }
}
