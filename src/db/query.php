<?php

namespace Vosiz\VaTools\Db;

/**
 * SQL query builder
 */
class QueryBuilder {

    const IDENTIFIER_PATTERN = '/^[A-Za-z_][A-Za-z0-9_]*(\.[A-Za-z_][A-Za-z0-9_]*)?$/';

    private $pdo;

    private $table = '';
    private $type = 'SELECT';
    private $columns = ['*'];
    private $conditions = [];
    private $joins = [];
    private $orderby = [];
    private $limit = NULL;
    private $wparams = [];
    private $data = [];

    private $Query;         public function GetQuery() { return $this->Query; } // executed query / last query

    /**
     * Constructor
     * @param PDO $pdo Database connector
     * @param string $table Database table
     * @throws DbException
    */
    public function __construct(\PDO $pdo, string $table) {

        $this->pdo = $pdo;
        $this->table = $this->Quote($table);
    }

    /**
     * SELECT clause
     * @param array $columns Columns to select - raw SQL (expressions allowed), must not come from a request
     * @return QueryBuilder
    */
    public function Select(array $columns = ['*']) {

        $this->type = 'SELECT';
        $this->columns = $columns;
        return $this;
    }

    /**
     * WHERE clause, another call joins with AND
     * @param string $condition Condition format (format: key = ?...)
     * @param array $params Conditions params
     * @return QueryBuilder
    */
    public function Where(string $condition, array $params = []) {

        return $this->AddCondition('AND', $condition, $params);
    }

    /**
     * AND WHERE clause
     * @param string $condition Additional condition (format: key = ?...)
     * @param array $params Conditions params
     * @return QueryBuilder
    */
    public function AndWhere(string $condition, array $params = []) {

        return $this->AddCondition('AND', $condition, $params);
    }

    /**
     * OR WHERE clause
     * @param string $condition Additional condition (format: key = ?...)
     * @param array $params Conditions params
     * @return QueryBuilder
    */
    public function OrWhere(string $condition, array $params = []) {

        return $this->AddCondition('OR', $condition, $params);
    }

    /**
     * ORDER BY clause
     * @param string|array $order Column name (ASC), or [column => 'ASC'|'DESC', ...]
     * @return QueryBuilder
     * @throws DbException
     */
    public function OrderBy($order) {

        if(is_string($order))
            $order = [$order => 'ASC'];

        $this->orderby = [];
        foreach($order as $col => $dir) {

            $dir = strtoupper($dir);
            if($dir !== 'ASC' && $dir !== 'DESC')
                throw new DbException("Invalid order direction for column %s", $col);

            $this->orderby[$this->Quote($col)] = $dir;
        }

        return $this;
    }

    /**
     * LIMIT clause
     * @param int|null $limit Max rows to return, NULL for no limit
     * @return QueryBuilder
     * @throws DbException
     */
    public function Limit(?int $limit) {

        if($limit !== NULL && $limit < 0)
            throw new DbException("Limit cannot be negative, %s given", $limit);

        $this->limit = $limit;
        return $this;
    }

    /**
     * Insert clause
     * @param array $data (format [column => value])
     * @return QueryBuilder
     * @throws DbException
    */
    public function Insert(array $data) {

        $this->type = 'INSERT';
        $this->data = $this->QuoteKeys($data);
        return $this;
    }

    /**
     * Update clause
     * @param array $data (format [column => value])
     * @return QueryBuilder
     * @throws DbException
    */
    public function Update(array $data) {

        $this->type = 'UPDATE';
        $this->data = $this->QuoteKeys($data);
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
     * @return array|int rows (objects) for SELECT, number of affected rows otherwise
     * @throws DbException
    */
    public function Execute() {

        try {

            $this->Query = '';
            $params = $this->wparams;
            switch ($this->type) {
                case 'SELECT':
                    $this->Query = $this->BuildSelect();
                    break;
                case 'INSERT':
                    $this->Query = $this->BuildInsert();
                    $params = array_values($this->data);
                    break;
                case 'UPDATE':
                    $this->Query = $this->BuildUpdate();
                    $params = array_merge(array_values($this->data), $this->wparams);
                    break;
                case 'DELETE':
                    $this->Query = $this->BuildDelete();
                    break;

                default:
                    throw new \Exception("Db.Execute: unknown CRUD method");
            }

            $stmt = $this->pdo->prepare($this->Query);
            $stmt->execute($params);

            return $this->type === 'SELECT' ? $stmt->fetchAll(\PDO::FETCH_OBJ) : $stmt->rowCount();

        } catch(DbException $exc) {

            throw $exc;

        } catch(\Exception $exc) {

            throw DbException::CreateInner($exc, $exc->getMessage());
        }

    }


    /**
     * Adds condition with its conjunction
     * @param string $conjunction AND|OR, left out for the first condition
     * @param string $condition Condition format (format: key = ?...)
     * @param array $params Conditions params
     * @return QueryBuilder
     */
    private function AddCondition(string $conjunction, string $condition, array $params) {

        $this->conditions[] = empty($this->conditions) ? $condition : "$conjunction $condition";
        $this->wparams = array_merge($this->wparams, $params);
        return $this;
    }

    /**
     * Checks identifier (table, column or table.column) and wraps it in backticks
     * @param string $identifier
     * @return string
     * @throws DbException
     */
    private function Quote(string $identifier) {

        if(!preg_match(self::IDENTIFIER_PATTERN, $identifier))
            throw new DbException("Invalid identifier: %s", $identifier);

        return '`'.str_replace('.', '`.`', $identifier).'`';
    }

    /**
     * Quotes keys (column names) of data array
     * @param array $data (format [column => value])
     * @return array
     * @throws DbException
     */
    private function QuoteKeys(array $data) {

        $quoted = [];
        foreach($data as $col => $value) {

            $quoted[$this->Quote((string)$col)] = $value;
        }

        return $quoted;
    }

    /**
     * Builds SELECT arguments
     * @return string query
    */
    private function BuildSelect() {

        $columns = implode(', ', $this->columns);
        $joins = implode(' ', $this->joins);
        $where = $this->conditions ? 'WHERE ' . implode(' ', $this->conditions) : '';
        $order = '';
        if(!empty($this->orderby)) {
            $parts = [];
            foreach($this->orderby as $col => $dir) {
                $parts[] = "$col $dir";
            }
            $order = 'ORDER BY ' . implode(', ', $parts);
        }
        $limit = $this->limit !== NULL ? "LIMIT {$this->limit}" : '';

        return "SELECT $columns FROM {$this->table} $joins $where $order $limit";
    }

    /**
     * Build INSERT arguments
     * @return string query
    */
    private function BuildInsert() {

        $columns = implode(', ', array_keys($this->data));
        $placeholders = implode(', ', array_fill(0, count($this->data), '?'));

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
