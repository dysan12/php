<?php
namespace App\Logs;


interface ILog
{
    public function saveMessage(string $module, \Exception $exception);
}