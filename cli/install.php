<?php
namespace Pressmind;
use Exception;
use Pressmind\Log\Writer;
use Pressmind\REST\Client;

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';

$namespace = 'Pressmind\ORM\Object';

$models = [
    '\Airline',
    '\Airport',
    '\Bank',
    '\CategoryTree',
    '\CategoryTree\Item',
    '\CheapestPriceSpeed',
    '\FulltextSearch',
    '\MediaObject',
    '\MediaObject\DataType\Categorytree',
    '\MediaObject\DataType\File',
    '\MediaObject\DataType\Link',
    '\MediaObject\DataType\Location',
    '\MediaObject\DataType\ObjectLink',
    '\MediaObject\DataType\Picture',
    '\MediaObject\DataType\Picture\Derivative',
    '\MediaObject\DataType\Table',
    '\Route',
    '\Search',
    '\Touristic\Booking\Earlybird',
    '\Touristic\Booking\Package',
    '\Touristic\Date',
    '\Touristic\ExtrasToMediaObject',
    '\Touristic\Housing\Package',
    '\Touristic\Insurance',
    '\Touristic\Insurance\Group',
    '\Touristic\Insurance\InsuranceToGroup',
    '\Touristic\Insurance\PriceTable',
    '\Touristic\Option',
    '\Touristic\Option\Description',
    '\Touristic\Pickupservice',
    '\Touristic\Pickupservice\Option',
    '\Touristic\SeasonalPeriod',
    '\Touristic\Startingpoint',
    '\Touristic\Startingpoint\Option',
    '\Touristic\Startingpoint\Option\ZipRange',
    '\Touristic\Transport'
];
foreach ($models as $model) {
    try {
        /** @var ORM\Object\AbstractObject $model_name */
        $model_name = $namespace . $model;
        Writer::write('Creating database table for model: ' . $model_name, Writer::OUTPUT_BOTH, 'install.log');
        $scaffolder = new DB\Scaffolder\Mysql(new $model_name());
        $scaffolder->run();
    } catch (Exception $e) {
        Writer::write($model_name, Writer::OUTPUT_BOTH, 'install_errors.log');
        Writer::write($e->getMessage(), Writer::OUTPUT_BOTH, 'install_errors.log');
    }
}
try {
    Writer::write('Requesting and parsing information on media object types ...', Writer::OUTPUT_BOTH, 'install.log');
    $importer = new Import();
    $ids = [];
    $client = new Client();
    $response = $client->sendRequest('ObjectType', 'getAll');
    $config = Registry::getInstance()->get('config');
    $media_types = [];
    foreach ($response->result as $item) {
        $media_types[$item->id_type] = $item->type_name;
        $ids[] = $item->id_type;
    }
    $config['data']['media_types'] = $media_types;
    Registry::getInstance()->get('config_adapter')->write($config);
    Registry::getInstance()->add('config', $config);
    $importer->importMediaObjectTypes($ids);
} catch (Exception $e) {
    Writer::write($e->getMessage(), Writer::OUTPUT_BOTH, 'install_errors.log');
}
