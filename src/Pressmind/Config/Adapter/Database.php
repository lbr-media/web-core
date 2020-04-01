<?php


namespace Pressmind\Config\Adapter;


use Pressmind\DB\Adapter\AdapterInterface;

class Database implements \Pressmind\Config\AdapterInterface
{
    /**
     * @var string
     */
    private $_database_table_name;

    /**
     * @var AdapterInterface
     */
    private $_database_adapter;

    /**
     * @var array
     */
    private $_database_table_field_map;

    /**
     * Database constructor.
     * @param string $databaseTableName
     * @param string $environment
     * @param array $options
     */
    public function __construct($databaseTableName, $environment, $options = [])
    {
        $this->_database_adapter = $options['database_adapter'];
        $this->_database_table_name = $databaseTableName;
        $this->_database_table_field_map = $options['database_table_field_map'];
    }

    public function read()
    {
        $config = [
            'development' => [],
            'production' => [],
            'testing' => [],
        ];
        $tmp_config = json_decode(file_get_contents($this->_config_file), true);
        $config['development'] = $tmp_config['development'];
        $config['production'] = array_merge($tmp_config['development'], $tmp_config['production']);
        $config['testing'] = array_merge($tmp_config['development'], $tmp_config['testing']);
        return $config[$this->_environment];
    }

    public function write($data)
    {
        // TODO: Implement write() method.
    }
}
