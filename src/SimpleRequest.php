<?php

namespace App;

class SimpleRequest // more simple ;)
{
    const METHOD_POST = 'POST';
    const METHOD_GET = 'GET';

    private $headers;
    private $data;
    private $method;
    private $uri;
    private static $instance = null;

    private function __construct()
    {
        $this->init();
    }

    private function __clone() {}
    private function __wakeup() {}

    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    public function init()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri    = $_SERVER['REQUEST_URI'];

        $this->headers = array_filter($_SERVER, function ($value, $key) {
            return strstr($key, 'HTTP_') !== false;
        }, ARRAY_FILTER_USE_BOTH);

        $this->data['get'] = $_GET;
        $this->data['post'] = $_POST;
        $this->data['post_raw'] = file_get_contents('php://input');
    }

    public function isPost() : bool
    {
        return $this->method === self::METHOD_POST;
    }

    public function get(string $name = '') : ?string
    {
        return $this->data['get'][$name] ?? null;
    }

    public function post(string $name = '') : ?string
    {
        return $this->data['post'][$name] ?? null;
    }

    public function postRaw() : string
    {
        return $this->data['post_raw'] ?: '';
    }
}
