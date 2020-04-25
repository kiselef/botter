<?php

namespace App\Service\Handler;

/**
 * @property string $title
 * @property string $date
 * @property string $text
 * @property string $link_vk
 * @property string $screen_name
 * @property array $attach
 * @property int $likes
 * @property int $comments
 * @property int $views
 * @property int $id
 * @property int $owner_id
 * @package App\Service\Handler
 */
class VkPost
{
    const ATTACH_TYPE_PHOTO = 'photo';

    private $attributes = [];
    private $popularity;

    public function __construct(array $item)
    {
        $this->attributes = [
            'id' => $item['id'],
            'owner_id' => $item['owner_id'],
            'date' => $item['date'],
            'text' => $item['text'],
            'views' => (int) $item['views']['count'],
            'reposts' => (int) $item['reposts']['count'],
            'comments' => (int) $item['comments']['count'],
            'likes' => (int) $item['likes']['count'],
            'screen_name' => $item['screen_name'] ?? null,
            'link_vk' => 'https://vk.com/wall-' . abs($item['owner_id']) . '_' . $item['id'],
        ];

        if (isset($item['attachments'])) {
            foreach ($item['attachments'] as $attach) {
                switch ($attach['type']) {
                    case 'photo':
                        $this->attributes['attach'] = [
                            'type' => self::ATTACH_TYPE_PHOTO,
                            'url' => array_pop($attach['photo']['sizes'])['url']
                        ];
                }
            }
        }
    }

    public function isPhoto(): bool
    {
        return isset($this->attach['type']) && $this->attach['type'] === self::ATTACH_TYPE_PHOTO;
    }

    public function getPopularity(): int
    {
        if (is_null($this->popularity)) {
            $this->popularity = $this->likes + $this->comments + intdiv($this->views, 100);
        }

        return $this->popularity;
    }

    public function __get($name)
    {
        return $this->attributes[$name] ?? null;
    }
}
