<?php

namespace Core;

use Core\QueryBuilder;

class BaseModel
{
    protected $table;
    protected static $builder = null;
    protected $attributes = [];
    protected $primaryKey = null;

    // public static function get()
    // {
    //     return $this->builder;
    // }

    public function setAttributes($data)
    {
        if (!is_array($data)) {
            return;
        }
        $this->attributes = $data;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        }

        return null;
    }

    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    public function getTable()
    {
        return $this->table;
    }

    public static function __callStatic($name, $arguments)
    {
        $model = new static;
        $queryBuilder = new QueryBuilder($model);
        if (!method_exists($model, $name) && method_exists($queryBuilder, $name)) {
            self::$builder = call_user_func_array([$queryBuilder, $name], $arguments);

            return self::$builder;
        }
    }
}
