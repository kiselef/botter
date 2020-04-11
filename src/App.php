<?php

namespace App;


class App
{
    private static $instance;

    private function __construct()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __clone() {}
    private function __wakeup() {}
}
