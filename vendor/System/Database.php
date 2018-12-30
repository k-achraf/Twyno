<?php
namespace System;

use PDO;
use PDOException;

class Database
{
    /**
     * Application object
     * @var Application $app
     */
    private $app;

    /**
     * PDO connection
     * @var PDO $DB
     */
    private static $DB;

    /**
     * table name
     * @var static $table
     */
    private $table;

    /**
     * data container
     * @var array $data
     */
    private $data = [];

    /**
     * bindings data
     * @var array $bindings
     */
    private $bindings = [];

    /**
     * last inserted id to table
     * @var int $lastId
     */
    private $lastId;

    /**
     * wheres
     * @var array $wheres
     */
    private $wheres = [];

    /**
     * Order By
     * @var array
     */
    private $orderBy = [];

    /**
     * selects
     * @var array
     */
    private $selects = [];

    /**
     * limit
     * @var int
     */
    private $limit;

    /**
     * offset
     * @var int
     */
    private $offset;

    /**
     * joins
     * @var array
     */
    private $joins = [];

    /**
     * total rows
     * @var int $rows
     */
    private $rows = 0;

    /**
     * Database constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        if (!$this->isConnected()){
            $this->connect();
        }
    }

    /**
     * check if there is any connection to DB
     * @return bool
     */
    public function isConnected()
    {
        return static::$DB instanceof PDO;
    }

    /**
     * connect to the DB
     */
    public function connect()
    {
        $data = $this->app->file->requireFile('config.php');
        extract($data);
        try {
            static::$DB = new PDO('mysql:host=' . $server . ';dbname=' . $dbname , $dbuser , $dbpass);

            static::$DB->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE , PDO::FETCH_OBJ);
            static::$DB->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);
            static::$DB->exec('SET NAMES utf8');

        }
        catch (PDOException $e){
            die($e->getMessage());
        }
    }

    /**
     * get Database connection PDO object
     * @return PDO
     */
    public function connection()
    {
        return static::$DB;
    }

    /***********************************/

    /**
     * set the table name
     * @param string $table
     * @return $this
     */
    public function table($table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * set the data thet will be stored on the db table
     * @param string $table
     * @return object
     */
    public function from($table)
    {
        return $this->table($table);
    }

    /**
     * @param mixed $key
     * @param mixed $value
     * @return $this
     */
    public function data($key , $value = null)
    {
        if (is_array($key)){
            $this->data = array_merge($this->data , $key);
            $this->addToBindings($key);
        }
        else{
            $this->data[$key] = $value;
            $this->addToBindings($value);
        }
        return $this;
    }

    /**
     * insert data into DB
     * @param string|null $table
     * @return $this
     */
    public function insert($table = null)
    {
        if ($table){
            $this->table = $table;
        }
        $sql = 'INSERT INTO ' . $this->table . ' SET ';
        $sql .= $this->setField();

        $this->query($sql , $this->bindings);
        $this->lastId = $this->connection()->lastInsertId();

        return $this;
    }

    /**
     * update data in DB
     * @param string|null $table
     * @return $this
     */
    public function update($table = null)
    {
        if ($table){
            $this->table = $table;
        }
        $sql = 'UPDATE ' . $this->table . ' SET ';

        $sql .= $this->setField();
        if ($this->wheres){
            $sql .= ' WHERE ' . implode(' ' , $this->wheres);
        }

        $this->query($sql , $this->bindings);

        $this->reset();
        return $this;
    }

    /**
     * fetch a table and return once value
     * @param string $table
     * @return \stdClass|null
     */
    public function fetch($table = null)
    {
        if ($table){
            $this->table = $table;
        }

        $sql = $this->fetchStatement();

        $result = $this->query($sql , $this->bindings)->fetch();

        $this->reset();
        return $result;
    }

    /**
     * fetch all records of table
     * @param string $table
     * @return array|null
     */
    public function fetchAll($table = null)
    {
        if ($table){
            $this->table = $table;
        }

        $sql = $this->fetchStatement();

        $query = $this->query($sql , $this->bindings);
        $results = $query->fetchAll();
        $this->rows = $query->rowCount();

        $this->reset();
        return $results;
    }

    /**
     * get rows count
     * @return int
     */
    public function rows()
    {
        return $this->rows;
    }

    /**
     * delete recorde from database
     * @param string|null $table
     * @return $this
     */
    public function delete($table = null)
    {
        if ($table){
            $this->table = $table;
        }
        $sql = 'DELETE FROM ' . $this->table . ' ';

        if ($this->wheres){
            $sql .= ' WHERE ' . implode(' ' , $this->wheres);
        }

        $this->query($sql , $this->bindings);

        $this->reset();
        return $this;
    }

    /**
     * add new where clause
     * @return $this
     */
    public function where()
    {
        $bindings = func_get_args();
        $sql = array_shift($bindings);

        $this->addToBindings($bindings);
        $this->wheres[] = $sql;

        return $this;
    }

    /**
     * get the last id
     * @return int
     */
    public function lastId(){
        return $this->lastId;
    }

    /**
     * add the given value to bindings
     * @param mixed $value
     * @return void
     */
    private function addToBindings($value)
    {
        if (is_array($value)){
            $this->bindings = array_merge($this->bindings , array_values($value));
        }
        else{
            $this->bindings[] = $value;
        }
    }

    /**
     * execute the given sql statement
     * @param string $sql
     * @return \PDOStatement
     */
    public function query()
    {
        $bindings = func_get_args();
        $sql = array_shift($bindings);

        if (count($bindings) == 1 and is_array($bindings[0])){
            $bindings = $bindings[0];
        }

        try{
            $query = $this->connection()->prepare($sql);

            foreach ($bindings as $key => $value){
                $query->bindValue($key + 1 , _e($value));
            }
            $query->execute();

            return $query;
        }
        catch (PDOException $e){
            echo $sql;
            pre($this->bindings);
            die($e->getMessage());
        }

    }

    /**
     * set fields for insert and update
     * @return string
     */
    private function setField()
    {
        $sql = '';
        $sql = rtrim($sql , ', ');

        foreach (array_keys($this->data) as $key){
            $sql .= '`' . $key . '` = ?, ';
        }

        $sql = rtrim($sql , ', ');
        return $sql;
    }

    /**
     * set select clause
     * @return $this
     */
    public function select()
    {
        $selects = func_get_args();

        if (count($selects) == 1 and is_array($selects[0])){
            $selects = $selects[0];
        }

        $this->selects = $selects;
        return $this;
    }

    /**
     * set joins clause
     * @param $join
     * @return $this
     */
    public function join($join)
    {
        $this->joins[] = $join;
        return $this;
    }

    public function limit($limit , $offset = 0)
    {
        $this->limit = $limit;
        $this->offset = $offset;

        return $this;
    }

    /**
     * set order by clause
     * @param string $column
     * @param string $sort
     * @return $this
     */
    public function orderBy($column , $sort = 'ASC')
    {
        $this->orderBy = [$column , $sort];
        return $this;
    }

    /**
     * prepare select statement
     * @return string
     */
    private function fetchStatement()
    {
        $sql = 'SELECT ';

        if ($this->selects){
            $sql .= implode(', ' , $this->selects);
        }
        else{
            $sql .= '*';
        }
        $sql .= ' FROM ' . $this->table . ' ';

        if ($this->joins){
            $sql .= implode(' ' , $this->joins);
        }

        if ($this->wheres){
            $sql .= ' WHERE ' . implode(' ' , $this->wheres);
        }

        if ($this->limit){
            $sql .= ' LIMIT ' . $this->limit;
        }

        if ($this->offset){
            $sql .= ' OFFSET ' . $this->offset;
        }

        if ($this->orderBy){
            $sql .= ' ORDER BY ' . implode(' ' , $this->orderBy);
        }
        return $sql;
    }

    /**
     * reset all data
     * @return void
     */
    private function reset()
    {
        $this->data = [];
        $this->bindings = [];
        $this->wheres = [];
        $this->orderBy = [];
        $this->limit = null;
        $this->offset = null;
        $this->joins = [];
        $this->table = null;
        $this->selects = [];
    }
}