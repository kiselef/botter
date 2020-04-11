<?php

namespace App\Service\Handler\Command;

class StartCommand extends BaseCommand
{
    public function execute() : void
    {
        $this->result[] = 'Привет ;) Это тестовый канал.';
        $this->result[] = 'Набери команду /stat и укажи id групп через пробел. Например,';
        $this->result[] = '';
        $this->result[] = '/stat 27838907 5421782';
        $this->result[] = '';
        $this->result[] = 'выведет самую популярную (по коментариям) запись из последних в каждой группе.';
        $this->result[] = '';
        $this->result[] = 'Расчет займет некоторое время.';
    }

    public function getResult()
    {
        return join("\n", $this->result);
    }
}
