<?php

namespace App\Service\Telegram\Message;

abstract class TelegramMessage
{
    protected $options = [];
    protected $default_options = [
        'disable_web_page_preview' => true,
    ];
    protected $text_field_name = 'text';

    abstract public function getMethod(): string;

    public function addText(string $text)
    {
        $message = $this->options[$this->text_field_name] ?? '';
        $this->options[$this->text_field_name] = $message . $text;
    }

    public function __construct(array $options)
    {
        $this->options = $options;
    }

    public function getOptions()
    {
        return array_merge($this->default_options, $this->options);
    }

    public function isValid()
    {
        $not_empty_options = array_filter($this->options, function ($option) {
            return !empty($option);
        });

        if (! empty(array_diff($this->required(), array_keys($not_empty_options)))) {
            return false;
        }

        return true;
    }

    public function required(): array
    {
        return [];
    }
}
