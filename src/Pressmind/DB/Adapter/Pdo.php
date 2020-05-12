<?php

namespace Pressmind\DB\Adapter;

use \Exception;
use \PDOStatement;
use \stdClass;

/**
 * Class Pdo
 * @package Pressmind\DB\Adapter
 */
class Pdo implements AdapterInterface
{
    /**
     * @var PDOStatement
     */
    private $statement;

    /**
     * @var \PDO
     */
    private $databaseConnection;

    /**
     * @var string
     */
    private $table_prefix;

    /**
     * Pdo constructor.
     * @param \Pressmind\DB\Config\Pdo $config
     */
    public function __construct($config)
    {
        $this->databaseConnection = new \PDO('mysql:host=' . $config->host . ';port=' . $config->port . ';dbname=' . $config->dbname . ';charset=utf8', $config->username, $config->password);
        $this->table_prefix = $config->table_prefix;
    }

    /**
     * @param string $query
     * @return bool|PDOStatement|void
     */
    public function prepare($query)
    {
        $this->statement = $this->databaseConnection->prepare($query);
        return $this->statement;
    }

    /**
     * @param string $query
     * @param array|null $parameters
     * @throws Exception
     */
    public function execute($query, $parameters = null)
    {
        $this->prepare($query);
        if (!$this->statement->execute($parameters)) {
            $error = $this->statement->errorInfo();
            throw new Exception('PDO Database Error: ' . $error[0] . ', ' . $error[1] . ', ' . $error[2] . ' Query: ' .  $this->statement->queryString . print_r($parameters, true));
        }
    }

    /**
     * @param null $query
     * @param null $parameters
     * @return array|void
     * @throws Exception
     */
    public function fetchAll($query = null, $parameters = null)
    {
        if (!is_null($query)) {
            $this->statement = $this->databaseConnection->prepare($query);
            $this->statement->execute($parameters);
        }
        return $this->statement->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * @param null $query
     * @param null $parameters
     * @return null|stdClass
     * @throws Exception
     */
    public function fetchRow($query = null, $parameters = null)
    {
        $result = $this->fetchAll($query, $parameters);
        if (count($result) > 0) {
            return $result[0];
        }
        return null;
    }

    /**
     * @param string $tableName
     * @param array $data
     * @return mixed|void
     * @throws Exception
     */
    public function insert($tableName, $data)
    {
        $columns = [];
        $values = [];
        $value_replacements = [];
        foreach ($data as $column => $value) {
            $columns[] = $column;
            $values[] = $value;
            $value_replacements[] = '?';
        }
        $query = "REPLACE INTO " . $this->table_prefix . $tableName . "(`" . implode('`, `', $columns) . "`) VALUES(" . implode(', ', $value_replacements) . ")";
        $this->execute($query, $values);
        return $this->databaseConnection->lastInsertId();
    }

    /**
     * @param string $tableName
     * @param array $data
     * @param array $where
     * @throws Exception
     */
    public function update($tableName, $data, $where = [])
    {
        $columns = [];
        $values = [];
        foreach ($data as $column => $value) {
            $columns[] = $column;
            $values[] = $value;
        }
        $values[] = $where[1];
        $query = "UPDATE " . $this->table_prefix . $tableName . " SET `" . implode("` = ?, `", $columns) . "` = ? WHERE " . $where[0];
        $this->execute($query, $values);
    }

    /**
     * @param $tableName
     * @param $where
     * @throws Exception
     */
    public function delete($tableName, $where = [])
    {
        $query = "DELETE FROM " . $this->table_prefix . $tableName . " WHERE " . $where[0];
        $this->execute($query, [$where[1]]);
    }

    /**
     * @return string
     */
    public function getTablePrefix()
    {
        return $this->table_prefix;
    }
}
