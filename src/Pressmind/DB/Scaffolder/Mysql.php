<?php


namespace Pressmind\DB\Scaffolder;


use Exception;
use Pressmind\DB\Adapter\AdapterInterface;
use Pressmind\ORM\Object\AbstractObject;
use Pressmind\Registry;

/**
 * Class Mysql
 * @package Pressmind\DB\Scaffolder
 * Creates or alters MySQL/MariaDB database tables based on the definitions of an ORM object
 */
class Mysql
{

    /**
     * @var AbstractObject
     */
    private $_orm_object;

    /**
     * Mysql constructor.
     * @param AbstractObject $ormObject
     */
    public function __construct($ormObject)
    {
        $this->_orm_object = $ormObject;
    }

    /**
     * @throws Exception
     */
    public function run() {
        $sql = $this->_parseTableInfoToSQL($this->_orm_object->getPropertyDefinitions());
        /**@var AdapterInterface $db**/
        $db = Registry::getInstance()->get('db');
        $db->execute($sql);
    }

    /**
     * @param array $pFields
     * @param bool $pAlterTable
     * @return string
     * @throws Exception
     */
    private function _parseTableInfoToSQL($pFields, $pAlterTable = false)
    {
        $sql = "CREATE TABLE IF NOT EXISTS " . $this->_orm_object->getDbTableName() . " (";
        if (true == $pAlterTable) {
            $sql = "ALTER TABLE " . $this->_orm_object->getDbTableName() . " ";
        }
        $unique = array();
        $index = array();
        $i = 0;
        foreach ($pFields as $fieldName => $fieldInfo) {
            if ($fieldInfo['type'] != 'relation') {
                $additional_sql = array();
                $null_allowed = '';
                if(isset($fieldInfo['encrypt']) && $fieldInfo['encrypt'] == true) {
                    $fieldInfo['type'] = 'encrypted';
                    $fieldInfo['unique'] = false;
                    $fieldInfo['length'] = null;
                }
                if (TRUE === $fieldInfo['required']) {
                    $null_allowed .= "NOT NULL";
                } else if (FALSE === $fieldInfo['required']) {
                    $null_allowed .= "NULL";
                }
                if (isset($fieldInfo['unique']) && TRUE == $fieldInfo['unique']) {
                    $unique[] = $fieldName;
                }
                if(isset($fieldInfo['index']) && is_array($fieldInfo['index'])) {
                    foreach ($fieldInfo['index'] as $index_type) {
                        $index[$index_type][] = $fieldName;
                    }
                }
                $additional_sql[] = $null_allowed;
                if (isset($fieldInfo['default_value'])) {
                    $additional_sql[] = " DEFAULT '" . $fieldInfo['default_value'] . "'";
                }
                if (true == $pAlterTable) {
                    if ($i > 0) {
                        $sql .= ", ";
                    }
                    $sql .= "ADD COLUMN $fieldName " . $this->_mapDbFieldType($fieldName, $fieldInfo) . " " . implode(" ", $additional_sql);
                } else {
                    $sql .= "`$fieldName` " . $this->_mapDbFieldType($fieldName, $fieldInfo) . " " . implode(" ", $additional_sql) . ",";
                }
            }
            $i++;
        }
        if (false == $pAlterTable) {
            $sql .= " PRIMARY KEY (" . $this->_orm_object->getDbPrimaryKey() . ")";
        }
        if (count($unique) > 0 && false == $pAlterTable) {
            foreach ($unique as $unique_field_name) {
                $sql .= ", UNIQUE KEY " . $unique_field_name . " (" . $unique_field_name . ")";
            }
        }
        if (count($index) > 0 && false == $pAlterTable) {
            foreach ($index as $index_type => $index_field_names) {
                foreach ($index_field_names as $index_field_name) {
                    $sql .= ", " . strtoupper($index_type) . " " . $index_field_name .  " (" . $index_field_name . ")";
                }
            }
        }
        if (false == $pAlterTable) {
            $sql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
        }
        return $sql;
    }

    /**
     * @param string $pFieldName
     * @param array $pFieldInfo
     * @param bool $pReturnOnlyType
     * @return string
     */
    private function _mapDbFieldType($pFieldName, $pFieldInfo, $pReturnOnlyType = false)
    {
        $mapping_table = array(
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
            'enum' => 'ENUM'
        );
        $return = $mapping_table[$pFieldInfo['type']];
        if (true === $pReturnOnlyType) {
            return $return;
        }
        if (isset($pFieldInfo['validators']) && is_array($pFieldInfo['validators'])) {
            foreach ($pFieldInfo['validators'] as $validator_info) {
                if($validator_info['name'] == 'maxlength') {
                    if($pFieldInfo['type'] == 'string') {
                        $return = 'VARCHAR';
                    }
                    if($pFieldInfo['type'] != 'boolean') {
                        $return .= '(' . $validator_info['params'] . ')';
                    }
                }
                if($validator_info['name'] == 'inarray') {
                    $return = "ENUM('" . implode("','", $validator_info['params']) . "')";
                }
            }
        }
        if ($pFieldName == $this->_orm_object->getDbPrimaryKey() && false === $this->_orm_object->dontUseAutoIncrementOnPrimaryKey()) {
            $return .= ' AUTO_INCREMENT';
        }

        return $return;
    }

}
