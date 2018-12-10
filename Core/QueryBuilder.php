<?php

namespace Core;

use Core\DBConnection;
use PDO;

class QueryBuilder
{
    protected $operators = ['>', '<', '>=', '<=', '=', 'LIKE', '!=', 'BETWEEN'];
    protected $query;
    protected $where;
    protected $select = '*';
    protected $limit = '';

    protected $model;
    protected $connection;

    public function __construct($model)
    {
        $this->connection = DBConnection::getConnection();
        $this->model = $model;
    }

    public function where($column, $operator, $value)
    {
        $operator = strtoupper($operator);
        if (!in_array($operator, $this->operators)) {
            throw new \Exception('Operator is not allowed.');
        }
        if ($operator === 'BETWEEN') {
            if (!is_array($value)) {
                throw new \Exception('SQL Error: the value of BETWEEN must be an array.');
            }
            $value = $value[0] . '\', \'' . $value[1];
        }
        if (!$this->where) {
            $this->where .= "WHERE $column $operator '$value'";
        } else {
            $this->where .= " AND $column $operator '$value'";
        }

        return $this;
    }

    public function select(...$columns)
    {
        $this->select = implode(', ', $columns);

        return $this;
    }

    public function limit($start, $end = null)
    {
        if ($end === null) {
            $this->limit = " LIMIT $start";
        } else {
            $this->limit = " LIMIT $start, $end";
        }
    }

    public function find($value, $column = null)
    {
        $column = $this->model->getPrimaryKey() ?? 'id';
        $this->where($column, '=', $value)->limit(1);
        $this->mergeGetQuery();
        if (count($this->get()) === 0) {
            return $this->model;
        }
        
        return $this->get()[0];
    }

    public function first()
    {
        return $this->get()[0];
    }

    public function mergeGetQuery()
    {
        $table = $this->model->getTable();
        $this->query = "SELECT $this->select FROM $table $this->where $this->limit";
    }

    public function get()
    {
        $this->mergeGetQuery();
        $db = $this->connection->prepare($this->query);
        $modelArray = [];
        if ($db->execute()) {
            $results = $db->fetchAll();
            if (count($results) > 0) {
                foreach ($results as $value) {
                    $this->model->setAttributes($value);
                    $modelArray[] = $this->model;
                }
            }
        }
        
        return $modelArray;
    }
}
