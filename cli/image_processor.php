<?php
namespace Pressmind;

use Exception;
use ImagickException;
use Pressmind\Image\Download;
use Pressmind\Image\Processor\Adapter\Factory;
use Pressmind\Image\Processor\Config;
use Pressmind\ORM\Object\MediaObject\DataType\Picture;
use Pressmind\ORM\Object\MediaObject\DataType\Picture\Derivative;

if(php_sapi_name() == 'cli') {
    putenv('ENV=DEVELOPMENT');
}

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';
$start_time = microtime(true);

echo number_format(microtime(true) - $start_time, 4) . " sec: image_processor.php started\n";

$db = Registry::getInstance()->get('db');
$config = Registry::getInstance()->get('config');
$result = $db->fetchAll("SELECT * FROM pmt2core_media_object_images WHERE path IS NULL");
$image_save_path = HelperFunctions::buildPathString([WEBSERVER_DOCUMENT_ROOT, $config['imageprocessor']['image_file_path']]);
if(!is_dir($image_save_path)) {
    mkdir($image_save_path, 0777, true);
}

foreach ($result as $image_result) {
    $image = new Picture();
    $image->fromStdClass($image_result);
    $download_url = $image->tmp_url;
    echo number_format(microtime(true) - $start_time, 4) . " sec: Downloading image from " . $download_url. "\n";
    $downloader = new Download();
    $query = [];
    $url = parse_url($image->tmp_url);
    parse_str($url['query'], $query);
    $filename = $downloader->download($download_url, $image_save_path, $image->id_media_object . '_' . $query['id']);
    $image->path = $image_save_path;
    $image->uri = $config['imageprocessor']['image_file_path'];
    $image->file_name = $filename;
    $image->update();

    foreach ($config['imageprocessor']['derivatives'] as $derivative_name => $derivative_config) {
        try {
            $imageProcessor = Factory::create($config['imageprocessor']['adapter']);
            $processor_config = Config::create($derivative_config);
            $path = $imageProcessor->process($processor_config, $image_save_path . DIRECTORY_SEPARATOR . $image->file_name, $derivative_name);
            $result[] = $path;
            $derivative = new Derivative();
            $derivative->id_image = $image->getId();
            $derivative->name = $derivative_name;
            $derivative->path = $path;
            $derivative->uri = '/' . $config['imageprocessor']['image_file_path'] . '/' . pathinfo($path)['filename'] . '.' . pathinfo($path)['extension'];
            $derivative->create();
        } catch(ImagickException | Exception $e) {
            echo 'Failed to process image: ' . $image->file_name .  "\n";
        }
    }
}

