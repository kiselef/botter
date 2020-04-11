<?php

namespace App\Service\Handler\Command;

use App\Service\Handler\Result\BaseResult;

/**
 * @property BaseResult $result
 * @property array      $option
 * @package App\Service\Bot\Command
 */
abstract class BaseCommand
{
    protected $result = [];
    protected $args = [];
    protected $options = [];

    abstract public function execute() : void;

    public function __construct(array $args = [])
    {
        $this->args = $args;

    }

    public function getArgs() : array
    {
        return $this->args;
    }

    public function setOptions(array $options)
    {
        $this->options = $options;
    }

//    public function getResult() : string
//    {
//        return $this->result;
//    }

    public function getResult()
    {
        return join("\n", $this->result);
    }
}
