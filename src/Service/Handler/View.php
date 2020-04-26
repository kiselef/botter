<?php

namespace App\Service\Handler;

class View
{
    public static function result(string $template, array $params = []): string
    {
        extract($params);
        ob_start();
        include __DIR__ . '/templates/' . $template . '.php';
        return ob_get_clean();
    }

    public static function resultByVK(string $template, array $params = []): string
    {
        $result = self::result($template, $params);
        return preg_replace('/\[(id\d+)\|(.+)\]/', '<a href="https://vk.com/$1">$2</a>', $result);
    }
}
