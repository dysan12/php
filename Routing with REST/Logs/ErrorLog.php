<?php

namespace src\Logs;


class ErrorLog implements ILog
{
    public function saveMessage(string $module, \Exception $exception)
    {
        $errorContent = '[ERROR][' . date('d-m-Y H:i:s') . ']' . 'Module ' . $module . '. ' . $exception->getMessage() . ' - File:' . $exception->getFile() . ' - Code:' . $exception->getCode() . ' - Line:' . $exception->getLine() . '\n';

        file_put_contents( 'errors.log', $errorContent, FILE_APPEND);
    }
}