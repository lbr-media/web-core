<?php


namespace Pressmind\DB\Typemapper;

use Exception;

class Mysql
{
    private $_orm_mapping_table = array(
        'int' => 'INT',
        'integer' => 'INT',
        'float' => 'FLOAT',
        'varchar' => 'VARCHAR',
        'date' => 'DATE',
        'datetime' => 'DATETIME',
        'DateTime' => 'DATETIME',
        'time' => 'TIME',
        'boolean' => 'TINYINT(1)',
        'text' => 'TEXT',
        'string' => 'TEXT',
        'longtext' => 'LONGTEXT',
        'blob' => 'BLOB',
        'longblob' => 'LONGBLOB',
        'encrypted' => 'BLOB',
        'enum' => 'ENUM',
        'relation' => null
    );

    private $_pressmind_mapping_table = array(
        'text' => 'longtext',
        'integer' => 'int',
        'int' => 'int',
        'table' => 'relation',
        'date' => 'datetime',
        'plaintext' => 'longtext',
        'wysiwyg' => 'longtext',
        'picture' => 'relation',
        'objectlink' => 'relation',
        'file' => 'relation',
        'categorytree' => 'relation',
        'location' => 'relation',
        'link' => 'relation',
        'key_value' => 'relation',
    );

    /**
     * @param $typeName
     * @return mixed
     * @throws Exception
     */
    public function mapTypeFromPressmindToORM ($typeName) {
        if(key_exists($typeName, $this->_pressmind_mapping_table)) {
            return $this->_pressmind_mapping_table[$typeName];
        } else {
            throw new Exception('Type ' . $typeName . ' does not exist in $_pressmind_mapping_table');
        }
    }

    /**
     * @param $typeName
     * @return mixed
     * @throws Exception
     */
    public function mapTypeFromORMToMysql($typeName) {
        if(key_exists($typeName, $this->_orm_mapping_table)) {
            return $this->_orm_mapping_table[$typeName];
        } else {
            throw new Exception('Type ' . $typeName . ' does not exist in $_orm_mapping_table');
        }
    }

    /**
     * @param $typeName
     * @return mixed
     * @throws Exception
     */
    public function mapTypeFromPressMindToMysql($typeName) {
        return($this->mapTypeFromORMToMysql($this->mapTypeFromPressmindToORM($typeName)));
    }
}
