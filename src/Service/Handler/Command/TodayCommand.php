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
        DEFAULT_POST_NUMBER = 1,
        DEFAULT_GROUP_ID = 27838907;

    public function execute() : void
    {
        if ($this->args) {
            $owner_id = $this->args[0];
            $limit = $this->args[1] ?? self::DEFAULT_POST_NUMBER;
            $response = $this->vk->wall($owner_id, $limit);
            $this->result[] = $this->getTodayStat($response);
        }
    }

    private function getTodayStat(array $info = [])
    {
        $group_name = $info['groups'] ? $info['groups'][0]['name'] : 'No Name';
        $result = "<$group_name>";
        foreach ($info['items'] as $item) {
            $content_info = [
                'name' => $group_name,
                'content' => $item['text'],
                'comments' => $item['comments']['count'],
                'likes' => $item['comments']['count'],
                'link_vk' => 'https://vk.com/wall-' . abs($item['owner_id']) . '_' . $item['id'],
            ];
            if (isset($item['attachments'])) {
                $attaches = $item['attachments'];
                foreach ($attaches as $attach) {
                    if (isset($attach['link'])) {
                        $content_info['link_source'] = $attach['link']['url'];
                    }
                }

            }

            $result .= "\n\n{$content_info['content']}\n{$content_info['link_vk']}";
            if (isset($content_info['link_source'])) {
                $result .= PHP_EOL . $content_info['link_source'] . PHP_EOL;
            }

            if ($content_info['comments'] > 15) {
                $result .= "\nКомментариев: {$content_info['comments']}";
            }
        }

        return $result;
    }

    public function getResult()
    {
        return join("\n", $this->result);
    }
}
