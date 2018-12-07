<?php

namespace Core;

use Core\QueryBuilder;

class BaseModel
{
    public $table;
    protected static $builder = null;

    public static function get()
    {
        return $this->builder;
    }

    public static function __callStatic($name, $arguments)
    {
        $model = new static;
        $queryBuilder = new QueryBuilder($model->table);
        if (!method_exists($model, $name) && method_exists($queryBuilder, $name)) {
            self::$builder = call_user_func_array([$queryBuilder, $name], $arguments);

            return self::$builder;
        }
    }
}
