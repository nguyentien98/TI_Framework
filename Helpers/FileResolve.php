<?php

namespace Helpers;

class FileResolve
{
    protected $file;

    public function __construct($file)
    {
        if (is_uploaded_file($file['tmp_name'])) {
            $this->file = $file;
            foreach ($this->file as $key => $value) {
                $this->$key = $value;
            }
        }
    }

    public function extension()
    {
        $fileName = $this->name;

        return substr($fileName, strrpos($fileName, '.') + 1);
    }

    public function move($path, $fileName = null)
    {
        if (!$fileName) {
            $fileName = $this->name;
        }

        $storagePath = dirname(__DIR__) . '/Storage/';
        if (file_exists($storagePath) && !file_exists($path = $storagePath . $path . '/')) {
            if (!mkdir($path, 0777)) {
                throw new \Exception('Fail to upload. Permission denied.');
            }
        }

        if (!is_writable($path)) {
            throw new \Exception('Permission denied.');
        }
        
        if (move_uploaded_file($this->file['tmp_name'], $path . $fileName)) {
            return $fileName;
        } else {
            return false;
        }
    }
}
