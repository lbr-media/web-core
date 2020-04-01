<?php
namespace Pressmind;

use Exception;
use Pressmind\Log\Writer;

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';
$args = $argv;

$importer = new Import();

if($args[1] == 'fullimport') {
    try {
        $importer->import();
        $importer->postImport();
    } catch(Exception $e) {

    }
}

if($args[1] == 'mediaobject' && is_numeric($args[2])) {
    try {
        Writer::write('Importing mediaobject ID: ' . $args[2], Writer::OUTPUT_BOTH, 'import.log');
        $importer->importMediaObject(intval($args[2]));
        $importer->postImport();
    } catch(Exception $e) {
        echo $e->getMessage();
    }
}

if($args[1] == 'objecttypes' && !empty($args[2])) {
    $ids = array_map('trim', explode(',', $args[2]));
    try {
        $importer->importMediaObjectTypes($ids);
    } catch(Exception $e) {

    }
}
