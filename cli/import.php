<?php
namespace Pressmind;

use Exception;
use Pressmind\Log\Writer;

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';
$args = $argv;

$importer = new Import();

switch ($args[1]) {
    case 'fullimport':
        Writer::write('Importing all media objects', Writer::OUTPUT_BOTH, 'import.log');
        try {
            $importer->import();
            $importer->postImport();
            Writer::write('Import done.', Writer::OUTPUT_BOTH, 'import.log');
        } catch(Exception $e) {
            Writer::write($e->getMessage(), Writer::OUTPUT_BOTH, 'import_error.log');
        }
        break;
    case 'mediaobject':
        if(!empty($args[2])) {
            Writer::write('Importing mediaobject ID(s): ' . $args[2], Writer::OUTPUT_BOTH, 'import.log');
            $ids = array_map('trim', explode(',', $args[2]));
            try {
                $importer->importMediaObjectsFromArray($ids);
                Writer::write('Import done.', Writer::OUTPUT_BOTH, 'import.log');
            } catch(Exception $e) {
                Writer::write($e->getMessage(), Writer::OUTPUT_BOTH, 'import_error.log');
            }
        } else {
            echo "Missing mediaobject id(s)";
        }
        break;
    case 'objecttypes':
        if(!empty($args[2])) {
            Writer::write('Importing objecttypes ID(s): ' . $args[2], Writer::OUTPUT_BOTH, 'import.log');
            $ids = array_map('trim', explode(',', $args[2]));
            try {
                $importer->importMediaObjectTypes($ids);
                Writer::write('Import done.', Writer::OUTPUT_BOTH, 'import.log');
            } catch(Exception $e) {
                Writer::write($e->getMessage(), Writer::OUTPUT_BOTH, 'import_error.log');
            }
        } else {
            echo "Missing objecttype id(s)";
        }
        break;
    case 'help':
    case '--help':
    case '-h':
    default:
        $helptext = "usage: import.php [fullimport | mediaobject | objecttypes] [<single id or commaseparated list of ids>]\n";
        $helptext .= "Example usages:\n";
        $helptext .= "php import.php fullimport\n";
        $helptext .= "php import.php mediaobject 123456 <only single id is allowed>\n";
        $helptext .= "php import.php objecttypes 123, 456 <singe or multiple ids allowed>\n";
        echo $helptext;
}

