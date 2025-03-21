<?php

namespace Vosiz\VaTools\Db;

/**
 * SQL query builder
 */
class QueryBuilder {

    private $pdo;

    private $table = '';
    private $type = 'SELECT';
    private $columns = ['*'];
    private $conditions = [];
    private $joins = [];
    private $orderby = 'id ASC';
    private $limit = '';
    private $wparams = []; 
    private $data = [];  
    
    private $Query;         public function GetQuery() { return $this->Query; } // executed query / last query

    /** 
     * Constructor
     * @param PDO $pdo Database connector
     * @param string $table Database table
    */
    public function __construct(\PDO $pdo, string $table) {

        $this->pdo = $pdo;
        $this->table = $table;
    }

    /** 
     * SELECT clause
     * @param array $columns Columns to select
     * @return QueryBuilder
    */
    public function Select(array $columns = ['*']) {

        $this->type = 'SELECT';
        $this->columns = $columns;
        return $this;
    }

    /** 
     * WHERE clause
     * @param string $condition Condition format (format: key = ?...)
     * @param array $params Conditions params
     * @return QueryBuilder
    */
    public function Where(string $condition, array $params = []) {

        $this->conditions[] = $condition;
        $this->wparams = array_merge($this->wparams, $params);
        return $this;
    }

    /** 
     * AND WHERE clause
     * @param string $condition Additional condition (format: key = ?...)
     * @param array $params Conditions params
     * @return QueryBuilder
    */
    public function AndWhere(string $condition, array $params = []) {

        return $this->where("AND $condition", $params);
    }

    /** 
     * OR WHERE clause
     * @param string $condition Additional condition (format: key = ?...)
     * @param array $params Conditions params
     * @return QueryBuilder
    */
    public function OrWhere(string $condition, array $params = []) {

        return $this->where("OR $condition", $params);
    }

    // TODO:
    // /** 
    //  * @return QueryBuilder
    // */
    // public function OrderBy(string $orderBy) {

    //     $this->orderby = $orderBy;
    //     return $this;
    // }

    // TODO:
    // /** 
    //  * @return QueryBuilder
    // */
    // public function Limit(int $limit) {

    //     $this->limit = $limit;
    //     return $this;
    // }

    /** 
     * Insert clause
     * @param array $data (format [column => value])
     * @return QueryBuilder
    */
    public function Insert(array $data) {

        $this->type = 'INSERT';
        $this->data = $data;
        return $this;
    }

    /** 
     * Update clause
     * @param array $data (format [column => value])
     * @return QueryBuilder
    */
    public function Update(array $data) {

        $this->type = 'UPDATE';
        $this->data = $data;
        return $this;
    }

    /** 
     * Delete clause
     * @return QueryBuilder
    */
    public function Delete() {

        $this->type = 'DELETE';
        return $this;
    }

    /** 
     * Executes query
     * @return QueryBuilder
    */
    public function Execute() {
        
        try {

            $this->Query = '';
            switch ($this->type) {
                case 'SELECT':
                    $this->Query = $this->BuildSelect();
                    break;
                case 'INSERT':
                    $this->Query = $this->BuildInsert();
                    break;
                case 'UPDATE':
                    $this->Query = $this->BuildUpdate();
                    break;
                case 'DELETE':
                    $this->Query = $this->BuildDelete();
                    break;

                default:
                    throw new \Exception("Db.Execute: unknown CRUD method");
            }

            $stmt = $this->pdo->prepare($this->Query);
            $stmt->execute($this->wparams);

            return $this->type === 'SELECT' ? $stmt->fetchAll(\PDO::FETCH_OBJ) : $stmt->rowCount() > 0;

        } catch(\Exception $exc) {

            throw new DbException($exc->getMessage());
        }

    }

    /** 
     * Builds SELECT arguments
     * @return string query
    */
    private function BuildSelect() {

        $columns = implode(', ', $this->columns);
        $joins = implode(' ', $this->joins);
        $where = $this->conditions ? 'WHERE ' . implode(' ', $this->conditions) : '';
        $order = $this->orderby ? "ORDER BY $this->orderby" : '';
        $limit = $this->limit ? "LIMIT $this->limit" : '';

        return "SELECT $columns FROM {$this->table} $joins $where $order $limit";
    }

    /** 
     * Build INSERT arguments
     * @return string query
    */
    private function BuildInsert() {

        $columns = implode(', ', array_keys($this->data));
        $placeholders = implode(', ', array_fill(0, count($this->data), '?'));
        $this->wparams = array_values($this->data);

        return "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
    }

    /** 
     * Build UPDATE arguments
     * @return string query
    */
    private function BuildUpdate() {

        if(empty($this->conditions))
            throw new DbException("UPDATE operation requires at least one condition!");

        $set = implode(', ', array_map(function ($key) {
            return "$key = ?";
        }, array_keys($this->data)));
        
        $this->wparams = array_merge(array_values($this->data), $this->wparams);
        $where = $this->conditions ? 'WHERE ' . implode(' ', $this->conditions) : '';

        return "UPDATE {$this->table} SET $set $where";
    }

    /** 
     * Build DELETE arguments
     * @return string query
    */
    private function BuildDelete() {

        if (empty($this->conditions)) {
            throw new DbException("DELETE operation requires at least one condition!");
        }
    
        $where = 'WHERE ' . implode(' ', $this->conditions);
        
        return "DELETE FROM {$this->table} $where";
    }
}

