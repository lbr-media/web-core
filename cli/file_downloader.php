<?php

use Pressmind\ORM\Object\MediaObject\DataType\File;
use Pressmind\Registry;

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';

$start_time = microtime(true);
echo number_format(microtime(true) - $start_time, 4) . " sec: image_processor.php started\n";

$db = Registry::getInstance()->get('db');
$config = Registry::getInstance()->get('config');

$result = $db->fetchAll("SELECT * FROM pmt2core_media_object_files WHERE file_path IS NULL");

$file_save_path = \Pressmind\HelperFunctions::buildPathString([WEBSERVER_DOCUMENT_ROOT, $config['file_download']['download_file_path']]);
if(!is_dir($file_save_path)) {
    mkdir($file_save_path, 0777, true);
}
echo number_format(microtime(true) - $start_time, 4) . " sec: " . print_r($result, true) . "\n";

foreach ($result as $file_result) {
    $file = new File();
    $file->fromStdClass($file_result);
    $download_url = $file->download_url;
    $file_name = $file->file_name;
    echo number_format(microtime(true) - $start_time, 4) . " sec: Downloading file from " . $download_url. "\n";
    $downloader = new \Pressmind\Image\Download();
    $downloader->download($download_url, $file_save_path, $file_name);
    $file->file_path = $file_save_path;
    $file->download_url = $config['file_download']['download_file_path'] . '/' . $file_name;
    $file->update();
}

