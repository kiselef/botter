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
}
