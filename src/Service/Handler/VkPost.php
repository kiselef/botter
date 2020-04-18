<?php

namespace App\Service\Handler;

/**
 * @property string $title
 * @property string $date
 * @property string $text
 * @property string $link_vk
 * @property array $attach
 * @package App\Service\Handler
 */
class VkPost
{
    const ATTACH_TYPE_PHOTO = 'photo';

    private $attributes = [];

    public function __construct(array $item)
    {
        $this->attributes = [
            'date' => $item['date'],
            'text' => $item['text'],
            'comments' => $item['comments']['count'],
            'likes' => $item['comments']['count'],
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

    public function isPhoto()
    {
        return isset($this->attach['type']) && $this->attach['type'] === self::ATTACH_TYPE_PHOTO;
    }

    public function __get($name)
    {
        return $this->attributes[$name] ?? null;
    }
}
