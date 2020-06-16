<?php
namespace Pressmind;

use Error;
use Exception;
use ImagickException;
use Pressmind\Image\Download;
use Pressmind\Image\Processor\Adapter\Factory;
use Pressmind\Image\Processor\Config;
use Pressmind\Log\Writer;
use Pressmind\ORM\Object\MediaObject\DataType\Picture;
use Pressmind\ORM\Object\MediaObject\DataType\Picture\Derivative;

if(php_sapi_name() == 'cli') {
    putenv('ENV=DEVELOPMENT');
}

$args = $argv;
$args[1] = isset($argv[1]) ? $argv[1] : null;

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';

Writer::write('Image processor started', WRITER::OUTPUT_FILE, 'image_processor.log');

$db = Registry::getInstance()->get('db');
$config = Registry::getInstance()->get('config');

try {
    /** @var Picture[] $result */
    $result = Picture::listAll(array('path' => 'IS NULL'));
} catch (Exception $e) {
    Writer::write($e->getMessage(), WRITER::OUTPUT_FILE, 'image_processor_error.log');
}
$image_save_path = HelperFunctions::buildPathString([WEBSERVER_DOCUMENT_ROOT, $config['imageprocessor']['image_file_path']]);

if(!is_dir($image_save_path)) {
    mkdir($image_save_path, 0777, true);
}

Writer::write('Processing ' . count($result) . ' images', WRITER::OUTPUT_FILE, 'image_processor.log');

foreach ($result as $image) {
    try {
        $download_url = $image->tmp_url;
        if($args[1] == 'nocache') {
            $download_url .= '&cache=0';
        }
        Writer::write('Downloading image from ' . $download_url, WRITER::OUTPUT_FILE, 'image_processor.log');
        $downloader = new Download();
        $query = [];
        $url = parse_url($image->tmp_url);
        parse_str($url['query'], $query);
        $filename = $downloader->download($download_url, $image_save_path, $image->id_media_object . '_' . $query['id']);
        Writer::write('Saving image ' . $filename, WRITER::OUTPUT_FILE, 'image_processor.log');
        $image->path = $image_save_path;
        $image->uri = $config['imageprocessor']['image_file_path'];
        $image->file_name = $filename;
        $image->update();
    } catch (Exception $e) {
        Writer::write($e->getMessage(), WRITER::OUTPUT_FILE, 'image_processor_error.log');
        continue;
    }
    Writer::write('Creating derivatives', WRITER::OUTPUT_FILE, 'image_processor.log');
    foreach ($config['imageprocessor']['derivatives'] as $derivative_name => $derivative_config) {
        Writer::write('Creating derivative ' . $derivative_name, WRITER::OUTPUT_FILE, 'image_processor.log');
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
            Writer::write('Derivative ' . $derivative_name . ' created: ' . $derivative->uri, WRITER::OUTPUT_FILE, 'image_processor.log');
        } catch(ImagickException | Exception | Error $e) {
            Writer::write($e->getMessage(), WRITER::OUTPUT_FILE, 'image_processor_error.log');
            continue;
        }
    }
}
Writer::write('Image processor finished', WRITER::OUTPUT_FILE, 'image_processor.log');
