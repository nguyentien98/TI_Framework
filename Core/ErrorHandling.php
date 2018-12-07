<?php

namespace Core;

class ErrorHandling
{
    public function handling()
    {
        $this->handleError();
        $this->handleException();
    }

    public function handleException()
    {
        set_exception_handler(function ($e) {
            $typeOfError = get_class($e);
            echo $typeOfError . ': ' . $e->getMessage();
            die;
        });
    }

    public function handleError()
    {
        set_error_handler(function ($code, $description, $fileName, $line) {
            echo $fileName . ': ' . $description . 'Line: ' . $line;
            die;
        });
    }
}
