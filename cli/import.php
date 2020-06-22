<?php
namespace Pressmind;

use Exception;
use Pressmind\Log\Writer;
use Pressmind\ORM\Object\MediaObject;

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';

$args = $argv;
$args[1] = isset($argv[1]) ? $argv[1] : null;

$importer = new Import();

switch ($args[1]) {
    case 'fullimport':
        Writer::write('Importing all media objects', Writer::OUTPUT_BOTH, 'import.log');
        try {
            $importer->import();
            $importer->postImport();
            if($importer->hasErrors()) {
                echo ("WARNING: Import threw errors:\n" . implode("\n", $importer->getErrors())) . "\nSEE " . Writer::getLogFilePath() . DIRECTORY_SEPARATOR . "import_errors.log for details\n";
            }
            Writer::write('Import done.', Writer::OUTPUT_BOTH, 'import.log');
        } catch(Exception $e) {
            Writer::write($e->getMessage(), Writer::OUTPUT_BOTH, 'import_error.log');
            echo "WARNING: Import threw errors:\n" . $e->getMessage() . "\nSEE " . Writer::getLogFilePath() . DIRECTORY_SEPARATOR . "import_errors.log for details\n";
        }
        break;
    case 'mediaobject':
        if(!empty($args[2])) {
            Writer::write('Importing mediaobject ID(s): ' . $args[2], Writer::OUTPUT_BOTH, 'import.log');
            $ids = array_map('trim', explode(',', $args[2]));
            try {
                $importer->importMediaObjectsFromArray($ids);
                Writer::write('Import done.', Writer::OUTPUT_BOTH, 'import.log');
                $importer->postImport();
                if($importer->hasErrors()) {
                    echo ("WARNING: Import threw errors:\n" . implode("\n", $importer->getErrors())) . "\nSEE " . Writer::getLogFilePath() . DIRECTORY_SEPARATOR . "import_errors.log for details\n";
                }
            } catch(Exception $e) {
                Writer::write($e->getMessage(), Writer::OUTPUT_BOTH, 'import_error.log');
                echo "WARNING: Import threw errors:\n" . $e->getMessage() . "\nSEE " . Writer::getLogFilePath() . DIRECTORY_SEPARATOR . "import_errors.log for details\n";
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
                if($importer->hasErrors()) {
                    echo ("WARNING: Import threw errors:\n" . implode("\n", $importer->getErrors())) . "\nSEE " . Writer::getLogFilePath() . DIRECTORY_SEPARATOR . "import_errors.log for details\n";
                }
            } catch(Exception $e) {
                Writer::write($e->getMessage(), Writer::OUTPUT_BOTH, 'import_error.log');
                echo "WARNING: Import threw errors:\n" . $e->getMessage() . "\nSEE " . Writer::getLogFilePath() . DIRECTORY_SEPARATOR . "import_errors.log for details\n";
            }
        } else {
            echo "Missing objecttype id(s)";
        }
        break;
    case 'depublish':
        if(!empty($args[2])) {
            Writer::write('Depublishing mediaobject ID(s): ' . $args[2], Writer::OUTPUT_BOTH, 'import.log');
            $ids = array_map('trim', explode(',', $args[2]));
            foreach ($ids as $id) {
                try {
                    $media_object = new MediaObject($id);
                    $media_object->visibility = 10;
                    $media_object->update();
                    Writer::write('Mediaobject ' . $id . ' successfully depublished (visibility set to 10/nobody)', Writer::OUTPUT_BOTH, 'import.log');
                } catch (Exception $e) {
                    Writer::write($e->getMessage(), Writer::OUTPUT_BOTH, 'import_error.log');
                    echo "WARNING: Depublish for id " . $id . "  failed:\n" . $e->getMessage() . "\nSEE " . Writer::getLogFilePath() . DIRECTORY_SEPARATOR . "import_errors.log for details\n";
                }
            }
        }
        break;
    case 'destroy':
        if(!empty($args[2])) {
            Writer::write('Destroying mediaobject ID(s): ' . $args[2], Writer::OUTPUT_BOTH, 'import.log');
            $ids = array_map('trim', explode(',', $args[2]));
            foreach ($ids as $id) {
                try {
                    $media_object = new MediaObject($id);
                    $media_object->delete();
                    Writer::write('Mediaobject ' . $id . ' successfully destroyed', Writer::OUTPUT_BOTH, 'import.log');
                } catch (Exception $e) {
                    Writer::write($e->getMessage(), Writer::OUTPUT_BOTH, 'import_error.log');
                    echo "WARNING: Destruction for mediaobject " . $id . "  failed:\n" . $e->getMessage() . "\nSEE " . Writer::getLogFilePath() . DIRECTORY_SEPARATOR . "import_errors.log for details\n";
                }
            }
        }
        break;
    case 'help':
    case '--help':
    case '-h':
    default:
        $helptext = "usage: import.php [fullimport | mediaobject | objecttypes] [<single id or commaseparated list of ids>]\n";
        $helptext .= "Example usages:\n";
        $helptext .= "php import.php fullimport\n";
        $helptext .= "php import.php mediaobject 123456, 78901234 <single or multiple ids allowed>\n";
        $helptext .= "php import.php objecttypes 123, 456 <singe or multiple ids allowed>\n";
        echo $helptext;
}

