<?php


namespace Pressmind\Log;


use Pressmind\HelperFunctions;
use Pressmind\Registry;
use \DateTime;
use \Exception;

class Writer
{
    const OUTPUT_SCREEN = 'screen';
    const OUTPUT_FILE = 'file';
    const OUTPUT_BOTH = 'both';

    /**
     * @param $log
     * @param string $output
     * @param string $filename
     * @return mixed|string
     * @throws Exception
     */
    static function write($log, $output = 'screen', $filename = 'messages.log')
    {
        $log_text = '';
        if($output == self::OUTPUT_SCREEN || $output == self::OUTPUT_BOTH) {
            $log_text = print_r($log, true);
            if(php_sapi_name() == "cli") {
                echo $log_text . "\n";
            } else {
                echo '<pre>' . $log_text . '</pre>';
            }
        }
        if($output == self::OUTPUT_FILE || $output == self::OUTPUT_BOTH) {
            $date = new DateTime();
            $config = Registry::getInstance()->get('config');
            $log_folder_name = isset($config['logging']['log_file_path']) ? $config['logging']['log_file_path'] : 'logs';
            $log_dir = HelperFunctions::buildPathString([APPLICATION_PATH, $log_folder_name]);
            if(!is_dir($log_dir)) {
                mkdir($log_dir, 0777, true);
            }
            $log_text = '[' . $date->format('Y-m-d H:i:s') . '] ' . print_r($log, true);
            if(file_put_contents($log_dir . DIRECTORY_SEPARATOR . $filename, $log_text . "\n", FILE_APPEND) == false) {
                throw new Exception('Failed to write logfile ' . $log_dir . DIRECTORY_SEPARATOR . $filename);
            }
        }
        return $log_text;
    }
}
