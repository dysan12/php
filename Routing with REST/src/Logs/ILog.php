<?php
namespace App\Logs;


interface ILog
{
    /**
     * Save message to appropriate file
     * @param string $module - name of module where problem occurred
     * @param \Exception $exception - instance of arose exception
     */
    public function saveMessage(string $module, \Exception $exception): void;
}