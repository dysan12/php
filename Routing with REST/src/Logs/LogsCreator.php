<?php

namespace App\Logs;

class LogsCreator implements ICreator
{
	/**
     * Create instance of ILog
     * @param string $class - class of logs [Error, Warning etc.]
     * @return ILog
     */
    public function create($class): ILog
    {
        $className = $class . 'Log';
        if (class_exists($className)) {
            return new $className();
        }

        return new ErrorLog();
    }
}