<?php

namespace App\Service\Handler\Command;

class StatCommand extends VkCommand
{
    public function execute() : void
    {
        if ($this->options) {
            if (count($this->options) > 5) {
                $this->result[] = "Максимальное количество параметров в дев режиме - 5.";
            }

            $this->result[] = 'Топ самых популярных последних новостей: ';
            $this->result[] = '';

            foreach ($this->options as $index => $group_id) {
                $response = $this->vk->wall($group_id, 15);
                $this->result[] = ($index + 1) . ") " . $this->getFamousItemByGroup($response);
            }
        }
    }

    private function getFamousItemByGroup(array $info = [])
    {
        $group_name = $info['groups'] ? $info['groups'][0]['name'] : 'No Name';

        usort($info['items'], function ($a, $b) {
            return $a['comments']['count'] + $a['likes']['count'] < $b['comments']['count'] + $b['likes']['count'] ? -1 : 1;
        });

        $item = array_pop($info['items']);
        if (isset($item['is_pinned']) && $item['is_pinned']) {
            $item = array_pop($info['items']);
        }
        $content_info = [
            'name' => $group_name,
            'content' => mb_strlen($item['text']) > 255 ? $item['text'] . '...' : mb_substr($item['text'], 0, 255),
            'comments' => $item['comments']['count'],
            'likes' => $item['comments']['count'],
            'views' => $item['views']['count'],
            'reposts' => $item['reposts']['count'],
            'link' => 'https://vk.com/wall-' . abs($item['owner_id']) . '_' . $item['id'],
        ];

        return "{$content_info['name']}
        
        {$content_info['content']}
        {$content_info['link']}
        
        Просмотров: {$content_info['views']}, Лайков: {$content_info['likes']}, Репостов: {$content_info['reposts']}, Комментариев: {$content_info['comments']}
        ";
    }

    public function getResult()
    {
        return join("\n", $this->result);
    }
}
