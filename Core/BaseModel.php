<?php

namespace Core;

use Core\DBConnection;

class BaseModel
{
    protected $connection;
    protected $operators = ['>', '<', '>=', '<=', 'LIKE'];
    protected static $query;
    protected static $where;
    protected static $select = '*';
    public $table;

    public function __construct()
    {
        $this->connection = DBConnection::getConnection();
    }

    public static function where($column, $operator, $value)
    {
        if (!in_array($operator, $this->operators)) {
            throw new \Exception('Operator is not allowed.');
        }
        if (self::$where) {
            self::$where .= "WHERE $column $operator '$value'";
        } else {
            self::$where .= " AND WHERE $column $operator '$value'";
        }
        $model = self;

        return $model;
    }

    public static function get()
    {
        self::$query = "SELECT $select FROM $table $where";

        return self;
    }

    public function toSql()
    {
        return $this->query;
    }
}
