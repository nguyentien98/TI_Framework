<?php

namespace Core;

use Core\DBConnection;

class QueryBuilder
{
    protected $operators = ['>', '<', '>=', '<=', '=', 'LIKE', '!='];
    protected $query;
    protected $where;
    protected $table;
    protected $select = '*';
    protected $connection;

    public function __construct($table)
    {
        $this->connection = DBConnection::getConnection();
        $this->table = $table;
    }

    public function where($column, $operator, $value)
    {
        if (!in_array($operator, $this->operators)) {
            throw new \Exception('Operator is not allowed.');
        }
        if (!$this->where) {
            $this->where .= "WHERE $column $operator '$value'";
        } else {
            $this->where .= " AND $column $operator '$value'";
        }

        return $this;
    }

    public function get()
    {
        $this->query = "SELECT $this->select FROM $this->table $this->where";
        return $this->query;
        $db = $this->connection->prepare($this->query);

        return $db->execute();
    }
}
