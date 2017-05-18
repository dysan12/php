<?php
namespace src\Logs;


interface ILog
{
    public function saveMessage(string $module, \Exception $exception);
}