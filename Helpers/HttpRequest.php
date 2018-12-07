<?php

namespace Helpers;

use Helpers\FileResolve;

class HttpRequest
{
    protected $allParams;
    
    public function __construct()
    {
        if ($this->method() === 'POST') {
            $this->resolverGetRequest();
            $this->resolverPostRequest();
        } elseif ($this->method() === 'GET') {
            $this->resolverGetRequest();
        }
    }

    public function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function isMethod($method = null)
    {
        return $_SERVER['REQUEST_METHOD'] === $method;
    }

    protected function convertDataToProperty($requestData)
    {
        $this->allParams = $requestData;
        if (count($requestData) > 0) {
            foreach ($requestData as $param => $value) {
                $this->$param = $value;
            }
        }
    }

    protected function convertFileToProperty($files)
    {
        foreach ($files as $param => $value) {
            if ($this->$param === null) {
                $this->$param = new FileResolve($value);
            }
        }
    }

    protected function resolverGetRequest()
    {
        $requestData = $_GET;
        $this->convertDataToProperty($requestData);
    }

    protected function resolverPostRequest()
    {
        $requestData = $_POST;
        $this->convertDataToProperty($requestData);
        if (count($_FILES) > 0) {
            $this->convertFileToProperty($_FILES);
        }
    }

    public function all()
    {
        return $this->allParams;
    }

    public function has($param)
    {
        return $this->$param !== null;
    }

    public function __get($param)
    {
        return null;
    }
}
