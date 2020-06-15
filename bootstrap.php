<?php
namespace Pressmind;
use Autoloader;
use Pressmind\DB\Adapter\Pdo;

/**
 * The pressmind lib needs three CONSTANTS to work
 * BASE_PATH: This is the path
 */
define('BASE_PATH', __DIR__);
define('APPLICATION_PATH', __DIR__);
define('WEBSERVER_DOCUMENT_ROOT', __DIR__);
define('WEBSERVER_HTTP', !empty($_SERVER['HTTPS']) ? 'http://' : 'https://' . $_SERVER['HTTP_HOST']);
define('ENV', 'development'); //For example purposes we set the ENV here, for real world applications it's a good idea to set an environment variable in a .htaccess file or in the webservers configuration

/**
 * Import the Autoloader
 * You can omit this if your using composers auto loading
 */
require_once BASE_PATH . '/src/Autoloader.php';
Autoloader::register();

/**
 * Loading the configuration
 * Here we will use the JSON config-adapter to load and parse a configuration file
 * you can also use YAML, XML or INI Files for configuration or even a simple array.
 * It is required that in every configuration the keys development, testing and production do exist.
 * @See the example config.json file for the required structure and options
 * @See the different config adapters for further information on YAML, XML and INI files (Pressmind\Config\Adapter)
 */
$config_adapter = new Config('json', HelperFunctions::buildPathString([BASE_PATH, 'config.json']), ENV);
$config = $config_adapter->read();

/**
 * Configure the database adapter
 */
$db_config = DB\Config\Pdo::create(
    $config['database']['host'],
    $config['database']['dbname'],
    $config['database']['username'],
    $config['database']['password']
);
/**
 * create the database adapter
 */
$db = new Pdo($db_config);

/**
 * Init the registry and add configuration and database adapter
 * It's important that a registry is set and that it has the elements 'config' and 'db' set at least, otherwise the library won't work at all
 * For sure you are encouraged to add other elements to the registry if needed
 */
$registry = Registry::getInstance();
$registry->add('config', $config);
$registry->add('config_adapter', $config_adapter);
$registry->add('db', $db);
