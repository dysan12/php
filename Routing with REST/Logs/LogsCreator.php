<?php

namespace src\Logs;

use src\Interfaces\ICreator;

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