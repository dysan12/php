<?php

namespace App\Logs;

class LogsCreator implements ICreator
{
    public function create($class): ILog
    {
        $className = $class . 'Log';
        if (class_exists($className)) {
            return new $className();
        }

        return new ErrorLog();
    }
}