<?php

namespace Pressmind;

use Custom\MediaType\Factory;
use Pressmind\DB\Adapter\Pdo;
use Pressmind\Log\Writer;
use Pressmind\ORM\Object\AbstractObject;
use Pressmind\ORM\Object\CategoryTree\Item;
use Pressmind\ORM\Object\MediaObject;
use Pressmind\ORM\Object\Route;
use Pressmind\ORM\Object\Touristic\Startingpoint\Option;
use Pressmind\REST\Client;
use \DirectoryIterator;
use \Exception;
use stdClass;

/**
 * Class Importer
 * @package Pressmind
 */
class Import
{

    /**
     * @var Client
     */
    private $_client;

    /**
     * @var string
     */
    private $_tmp_import_folder = 'import_ids';

    /**
     * @var array
     */
    private $_log = [];

    /**
     * @var array
     */
    private $_errors = [];

    /**
     * @var array
     */
    private $_visibilities = [30];

    /**
     * @var array
     */
    private $_states = [50];

    /**
     * @var array
     */
    private $_current_touristic_data_to_import = [];

    /**
     * @var array
     */
    private $_current_media_object_data_to_import = [];

    /**
     * @var array
     */
    private $_touristic_object_map = [
        'touristic_booking_packages' => '\Booking\Package',
        'touristic_dates' => '\Date',
        'touristic_seasonal_periods' => '\SeasonalPeriod',
        'touristic_transports' => '\Transport',
        'touristic_booking_earlybirds' => '\Booking\Earlybird',
        'touristic_housing_packages' => '\Housing\Package',
        'touristic_option_descriptions' => '\Option\Description',
        'touristic_options' => '\Option',
        'touristic_startingpoint_options' => '\Startingpoint\Option',
        'touristic_startingpoint_options_zip_ranges' => '\Startingpoint\Option\ZipRange',
        'touristic_startingpoints' => '\Startingpoint',
        'touristic_insurance_groups' => '\Insurance\Group',
        'touristic_insurance_to_group' => '\Insurance\InsuranceToGroup',
        'touristic_insurances' => '\Insurance',
        'touristic_insurances_price_tables' => '\Insurance\PriceTable',
    ];

    /**
     * @var float
     */
    private $_start_time;

    /**
     * @var float
     */
    private $_overall_start_time;

    /**
     * Importer constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->_start_time = microtime(true);
        $this->_overall_start_time = microtime(true);
        $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::__construct()', Writer::OUTPUT_FILE, 'import.log');
        $this->_client = new Client();
    }

    /**
     * @param integer|null $id_pool
     * @throws Exception
     */
    public function import($id_pool = null)
    {
        $conf = Registry::getInstance()->get('config');
        $allowed_object_types = array_keys($conf['data']['media_types']);
        $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::import()', Writer::OUTPUT_FILE, 'import.log');
        $params = [
            'visibility' => implode(',', $this->_visibilities),
            //'state' => implode(',', $this->_states),
            'id_media_object_type' => implode(',', $allowed_object_types)
        ];
        if (!is_null($id_pool)) {
            $params['id_pool'] = intval($id_pool);
        }
        $this->_importIds(0, $params);
        $this->_importMediaObjectsFromFolder();
    }

    /**
     * @param int $startIndex
     * @param array $params
     * @param int $numItems
     * @throws Exception
     */
    private function _importIds($startIndex, $params, $numItems = 50)
    {
        //$this->_log[] =  Writer::write(APPLICATION_PATH . DIRECTORY_SEPARATOR . $this->_tmp_import_folder . DIRECTORY_SEPARATOR . $item->id_media_object
        $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::_importIds()', Writer::OUTPUT_FILE, 'import.log');
        $params['startIndex'] = $startIndex;
        $params['numItems'] = $numItems;
        $response = $this->_client->sendRequest('Text', 'search', $params);
        $tmp_import_folder = APPLICATION_PATH . DIRECTORY_SEPARATOR . $this->_tmp_import_folder;
        if(!is_dir($tmp_import_folder)) {
            mkdir($tmp_import_folder);
        }
        foreach ($response->result as $item) {
            file_put_contents($tmp_import_folder . DIRECTORY_SEPARATOR . $item->id_media_object, print_r($item, true));
        }
        if (count($response->result) >= $numItems && $startIndex < $response->count) {
            $nextStartIndex = $startIndex + $numItems;
            $this->_importIds($nextStartIndex, $params, $numItems);
        }
    }

    /**
     * @throws Exception
     */
    private function _importMediaObjectsFromFolder()
    {
        $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::_importMediaObjectsFromFolder()', Writer::OUTPUT_FILE, 'import.log');
        $dir = new DirectoryIterator(APPLICATION_PATH . DIRECTORY_SEPARATOR . $this->_tmp_import_folder);
        foreach ($dir as $file_info) {
            if (!$file_info->isDot()) {
                $id_media_object = $file_info->getFilename();
                if ($this->importMediaObject($id_media_object)) {
                    unlink($file_info->getPathname());
                }
            }
        }
    }

    /**
     * @param $media_object_ids
     * @throws Exception
     */
    public function importMediaObjectsFromArray($media_object_ids)
    {
        foreach ($media_object_ids as $media_object_id) {
            $this->importMediaObject($media_object_id);
        }
    }

    /**
     * @param int $id_media_object
     * @return bool
     * @throws Exception
     */
    public function importMediaObject($id_media_object)
    {
        $id_media_object = intval($id_media_object);
        $db = Registry::getInstance()->get('db');
        $this->_start_time = microtime(true);
        $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . '--------------------------------------------------------------------------------', Writer::OUTPUT_FILE, 'import.log');
        $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::_importMediaObject(' . $id_media_object . ')', Writer::OUTPUT_FILE, 'import.log');
        $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::_importMediaObject(' . $id_media_object . '): REST Request started', Writer::OUTPUT_FILE, 'import.log');
        try {
            $response = $this->_client->sendRequest('Text', 'getById', ['ids' => $id_media_object, 'withTouristicData' => 1, 'withDynamicData' => 1]);
        } catch (Exception $e) {
            $response = null;
        }
        $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::_importMediaObject(' . $id_media_object . '): REST Request done', Writer::OUTPUT_FILE, 'import.log');
        $import_error = false;
        if (is_array($response) && count($response) > 0) {
            $this->_start_time = microtime(true);
            $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::_importMediaObject(' . $id_media_object . '): parsing data', Writer::OUTPUT_FILE, 'import.log');
            if (is_a($response[0]->touristic, 'stdClass')) {
                $starting_point_ids = $this->_importMediaObjectTouristicData($response[0]->touristic, $id_media_object);
            }
            if (is_array($response[0]->data)) {
                $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::importMediaObject(' . $id_media_object . '):  Deleting media_object_files', Writer::OUTPUT_FILE, 'import.log');
                $db->delete('pmt2core_media_object_files', ['id_media_object = ?', $id_media_object]);

                $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::importMediaObject(' . $id_media_object . '):  Deleting media_object_geodata', Writer::OUTPUT_FILE, 'import.log');
                $db->delete('pmt2core_media_object_geodata', ['id_media_object = ?', $id_media_object]);

                $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::importMediaObject(' . $id_media_object . '):  Deleting media_object_images', Writer::OUTPUT_FILE, 'import.log');
                $db->delete('pmt2core_media_object_images', ['id_media_object = ?', $id_media_object]);

                $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::importMediaObject(' . $id_media_object . '):  Deleting media_object_links', Writer::OUTPUT_FILE, 'import.log');
                $db->delete('pmt2core_media_object_links', ['id_media_object = ?', $id_media_object]);

                $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::importMediaObject(' . $id_media_object . '):  Deleting media_object_tables', Writer::OUTPUT_FILE, 'import.log');
                $db->delete('pmt2core_media_object_tables', ['id_media_object = ?', $id_media_object]);

                $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::importMediaObject(' . $id_media_object . '):  Deleting media_object_files', Writer::OUTPUT_FILE, 'import.log');
                $db->delete('pmt2core_media_object_files', ['id_media_object = ?', $id_media_object]);

                $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::importMediaObject(' . $id_media_object . '):  Deleting media_object_tree_items', Writer::OUTPUT_FILE, 'import.log');
                $db->delete('pmt2core_media_object_tree_items', ['id_media_object = ?', $id_media_object]);

                //$this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::importMediaObject(' . $id_media_object . '):  Deleting media_object_urls', Writer::OUTPUT_FILE, 'import.log');
                //$db->delete('pmt2core_media_object_urls', ['id_media_object = ?', $id_media_object]);

                $category_tree_ids = $this->_importMediaObjectData($response[0], $id_media_object);
            }
            if(!empty($starting_point_ids)) {
                $this->_importMediaObjectTouristicStartingPointOptions($starting_point_ids);
            }
            if(!empty($category_tree_ids)) {
                $this->_importCategoryTrees($category_tree_ids);
            }
            $media_object = new ORM\Object\MediaObject();
            $media_object->id = $response[0]->id_media_object;
            $media_object->id_pool = $response[0]->id_pool;
            $media_object->id_object_type = $response[0]->id_media_objects_data_type;
            $media_object->id_client = 348;
            $media_object->name = $response[0]->name;
            $media_object->code = $response[0]->code;
            $media_object->tags = $response[0]->tags;
            $media_object->visibility = $response[0]->visibility;
            $media_object->state = $response[0]->state;
            $media_object->valid_from = $response[0]->valid_from;
            $media_object->valid_to = $response[0]->valid_to;
            try {
                $old_media_object = new MediaObject();
                $old_media_object->read($response[0]->id_media_object);
                $old_media_object->delete();
                unset($old_media_object);
            } catch (Exception $e) {
                $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::importMediaObject(' . $id_media_object . '):  Deleting old object failed', Writer::OUTPUT_FILE, 'import.log');
            }
            $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::importMediaObject(' . $id_media_object . '):  Creating media object', Writer::OUTPUT_FILE, 'import.log');
            try {
                $media_object->create();
            } catch (Exception $e) {
                $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::importMediaObject(' . $id_media_object . '):  Creating media object failed: ' . $e->getMessage(), Writer::OUTPUT_FILE, 'import_error.log');
                $this->_errors[] = 'Importer::importMediaObject(' . $id_media_object . '):  Creating media object failed: ' . $e->getMessage();
            }
            $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::importMediaObject(' . $id_media_object . '):  Deleting Route entries', Writer::OUTPUT_FILE, 'import.log');
            $db->delete('pmt2core_routes', ['id_media_object = ?', $media_object->getId()]);
            $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::importMediaObject(' . $id_media_object . '):  Inserting Route entries', Writer::OUTPUT_FILE, 'import.log');
            $route = new Route();
            $route->id_media_object = $media_object->getId();
            $route->route = $media_object->getPrettyUrl();
            $route->language = 'de';
            $route->create();
            $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::importMediaObject(' . $id_media_object . '):  Routes updated', Writer::OUTPUT_FILE, 'import.log');
            $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::importMediaObject(' . $id_media_object . '):  Deleting CheapestPriceSpeed entries', Writer::OUTPUT_FILE, 'import.log');
            /**@var Pdo $db**/
            $db->delete('pmt2core_cheapest_price_speed', ['id_media_object = ?', $media_object->getId()]);
            $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::importMediaObject(' . $id_media_object . '):  Inserting CheapestPriceSpeed entries', Writer::OUTPUT_FILE, 'import.log');
            $media_object->setReadRelations(true);
            $media_object->readRelations();
            $media_object->insertCheapestPrice();
            $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::importMediaObject(' . $id_media_object . '):  CheapestPriceSpeed table updated', Writer::OUTPUT_FILE, 'import.log');
            if ($import_error == true) {
                $this->_revertCurrentImport();
            }
            $this->_current_touristic_data_to_import = [];
            $this->_current_media_object_data_to_import = [];
            unset($media_object);
            unset($response);
            $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::importMediaObject(' . $id_media_object . '):  Objects removed from heap', Writer::OUTPUT_FILE, 'import.log');
            $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . '--------------------------------------------------------------------------------', Writer::OUTPUT_FILE, 'import.log');
            $overall_time_elapsed = number_format(microtime(true) - $this->_overall_start_time, 4) . ' sec';
            $this->_log[] = Writer::write($overall_time_elapsed, Writer::OUTPUT_FILE, 'import.log');
            return ($import_error == false);
        } else {
            $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::importMediaObject(' . $id_media_object . '): RestClient-Request for Media Object ID: ' . $id_media_object . ' failed', Writer::OUTPUT_FILE, 'import_error.log');
            $this->_errors[] = 'Importer::importMediaObject(' . $id_media_object . '): RestClient-Request for Media Object ID: ' . $id_media_object . ' failed';
            $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . '--------------------------------------------------------------------------------', Writer::OUTPUT_FILE, 'import.log');
        }
        return false;
    }

    /**
     * @param array $touristic_data
     * @param integer $id_media_object
     * @return array
     * @throws Exception
     */
    private function _importMediaObjectTouristicData($touristic_data, $id_media_object)
    {
        $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::_importMediaObjectTouristicData(' . $id_media_object . '): parsing touristic data', Writer::OUTPUT_FILE, 'import.log');
        $this->_current_touristic_data_to_import = [];
        foreach ($touristic_data as $touistic_object_name => $touristic_objects) {
            $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::_importMediaObjectTouristicData(' . $id_media_object . '): Mapping ' . $touistic_object_name, Writer::OUTPUT_FILE, 'import.log');
            if (count($touristic_objects) == 0) {
                $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::_importMediaObjectTouristicData(' . $id_media_object . '): ' . $touistic_object_name . ' does not contain any data, skipping.', Writer::OUTPUT_FILE, 'import.log');
            }
            foreach ($touristic_objects as $touristic_object) {
                $class_name = '\Pressmind\ORM\Object\Touristic' . $this->_touristic_object_map[$touistic_object_name];
                try {
                    /**@var AbstractObject $object * */
                    $object = new $class_name();
                    $object->fromStdClass($touristic_object);
                    $this->_current_touristic_data_to_import[] = $object;
                    $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::_importMediaObjectTouristicData(' . $id_media_object . '): ' . $class_name . ' mapping successfull.', Writer::OUTPUT_FILE, 'import.log');
                } catch (Exception $e) {
                    $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::_importMediaObjectTouristicData(' . $id_media_object . '): ' . $class_name . ' mapping failed: ' . $e->getMessage(), Writer::OUTPUT_FILE, 'import_error.log');
                    $this->_errors[] = 'Importer::_importMediaObjectTouristicData(' . $id_media_object . '): ' . $class_name . ' mapping failed: ' . $e->getMessage();
                }
                unset($object);
                $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::_importMediaObjectTouristicData(' . $id_media_object . '): Object removed from heap', Writer::OUTPUT_FILE, 'import.log');
            }
        }
        $starting_point_ids = [];
        foreach ($this->_current_touristic_data_to_import as $touristic_object_to_import) {
            $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::_importMediaObjectTouristicData(' . $id_media_object . '): inserting touristic data for ' . get_class($touristic_object_to_import), Writer::OUTPUT_FILE, 'import.log');
            /**@var AbstractObject $touristic_object_to_import * */
            if(is_a($touristic_object_to_import,'Pressmind\ORM\Object\Touristic\Startingpoint')) {
                $starting_point_ids[] = $touristic_object_to_import->id;
            }
            try {
                $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::_importMediaObjectTouristicData(' . $id_media_object . '): deleting old data ' . get_class($touristic_object_to_import), Writer::OUTPUT_FILE, 'import.log');
                $touristic_object_to_import->delete();
                $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::_importMediaObjectTouristicData(' . $id_media_object . '): writing new data ' . get_class($touristic_object_to_import), Writer::OUTPUT_FILE, 'import.log');
                $touristic_object_to_import->create();
                $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::_importMediaObjectTouristicData(' . $id_media_object . '): ' . get_class($touristic_object_to_import) . ' created.', Writer::OUTPUT_FILE, 'import.log');
            } catch (Exception $e) {
                $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::_importMediaObjectTouristicData(' . $id_media_object . '): ' . get_class($touristic_object_to_import) . ' creation failed: ' . $e->getMessage(), Writer::OUTPUT_FILE, 'import_error.log');
                $this->_errors[] = 'Importer::_importMediaObjectTouristicData(' . $id_media_object . '): ' . get_class($touristic_object_to_import) . ' creation failed: ' . $e->getMessage();
            }
            unset($touristic_object_to_import);
            unset($touristic_data);
            $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::_importMediaObjectTouristicData(' . $id_media_object . '): Object removed from heap', Writer::OUTPUT_FILE, 'import.log');
        }
        return $starting_point_ids;
    }

    /**
     * @param array $startingpointIds
     * @throws Exception
     */
    private function _importMediaObjectTouristicStartingPointOptions($startingpointIds) {
        $this->_start_time = microtime(true);
        $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::_importMediaObjectTouristicStartingPointOptions(' . implode(',', $startingpointIds) . '): REST request started', Writer::OUTPUT_FILE, 'import.log');
        $response = $this->_client->sendRequest('StartingPoint', 'getById', ['ids' => implode(',', $startingpointIds)]);
        $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::_importMediaObjectTouristicStartingPointOptions(' . implode(',', $startingpointIds) . '): REST request done', Writer::OUTPUT_FILE, 'import.log');
        if (is_a($response, 'stdClass') && isset($response->result) && is_array($response->result)) {
            foreach ($response->result as $result) {
                if(is_a($result, 'stdClass') && isset($result->options) && is_array($result->options)) {
                    $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::_importMediaObjectTouristicStartingPointOptions(' . implode(',', $startingpointIds) . '): writing data', Writer::OUTPUT_FILE, 'import.log');
                    foreach ($result->options as $option) {
                        $starting_point_option = new Option();
                        unset($option->zip_ranges);
                        $starting_point_option->fromStdClass($option);
                        $starting_point_option->id_startingpoint = $result->id;
                        try {
                            $starting_point_option->create();
                        } catch (Exception $e) {
                            $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::_importMediaObjectTouristicStartingPointOptions(' . implode(',', $startingpointIds) . '): Error writing starting point option with ID ' . $starting_point_option->getId() . ': '. $e->getMessage(), Writer::OUTPUT_FILE, 'import_error.log');
                            $this->_errors[] = 'Importer::_importMediaObjectTouristicStartingPointOptions(' . implode(',', $startingpointIds) . '): Error writing starting point option with ID ' . $starting_point_option->getId() . ': '. $e->getMessage();
                        }
                        $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::_importMediaObjectTouristicStartingPointOptions(' . implode(',', $startingpointIds) . '): Starting point option with ID ' . $starting_point_option->getId() . ' written', Writer::OUTPUT_FILE, 'import.log');
                        unset($starting_point_option);
                        $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::_importMediaObjectTouristicStartingPointOptions(' . implode(',', $startingpointIds) . '): Object removed from heap', Writer::OUTPUT_FILE, 'import.log');
                    }
                }
            }
        }
        unset($response);
        $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::_importMediaObjectTouristicStartingPointOptions(' . implode(',', $startingpointIds) . '): Import finished', Writer::OUTPUT_FILE, 'import.log');
    }

    /**
     * @param $ids
     * @throws Exception
     */
    private function _importCategoryTrees($ids)
    {
        try {
            $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::_importCategoryTrees(): REST request started', Writer::OUTPUT_FILE, 'import.log');
            $response = $this->_client->sendRequest('Category', 'getById', ['ids' => implode(',', $ids)]);
            $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::_importCategoryTrees(): REST request done', Writer::OUTPUT_FILE, 'import.log');
            $this->_checkApiResponse($response);
            if (is_a($response, 'stdClass') && isset($response->result) && is_array($response->result)) {
                foreach ($response->result as $result) {
                    if (is_a($result, 'stdClass') && isset($result->tree) && !empty($result->tree)) {
                        $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::_importCategoryTrees(): Importing tree ID ' . $result->id , Writer::OUTPUT_FILE, 'import.log');
                        $tree = new ORM\Object\CategoryTree();
                        $tree->id = $result->id;
                        $tree->name = $result->name;
                        try {
                            $tree->delete();
                            $tree->create();
                        } catch (Exception $e) {
                            $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::_importCategoryTrees(): Error importing tree ID ' . $tree->id . ': ' . $e->getMessage(), Writer::OUTPUT_FILE, 'import_error.log');
                            $this->_errors[] = 'Importer::_importCategoryTrees(): Error importing tree ID ' . $tree->id . ': ' . $e->getMessage();
                        }
                        $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::_importCategoryTrees(): Tree import done ', Writer::OUTPUT_FILE, 'import.log');
                        if(isset($result->tree->item)) {
                            $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::_importCategoryTrees(): Importing tree items ' . $result->id , Writer::OUTPUT_FILE, 'import.log');
                            $this->_iterateCategoryTreeItems($result->id, $result->tree->item);
                        }
                        $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::_importCategoryTrees(): Importing tree items done', Writer::OUTPUT_FILE, 'import.log');
                    }
                }
            }
        } catch (Exception $e) {
            $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::_importCategoryTrees(): Import Error: ' . $e->getMessage(), Writer::OUTPUT_FILE, 'import_error.log');
            $this->_errors[] = 'Importer::_importCategoryTrees(): Import Error: ' . $e->getMessage();
        }
    }

    /**
     * @param $id_tree
     * @param $items
     * @param null $parent
     * @throws Exception
     */
    private function _iterateCategoryTreeItems($id_tree, $items, $parent = null) {
        $sort = 0;
        foreach ($items as $item) {
            $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::_iterateCategoryTreeItems(): Importing tree item ID ' . $item->id , Writer::OUTPUT_FILE, 'import.log');
            $sort++;
            $category_tree_item = new Item();
            $category_tree_item->id = $item->id;
            $category_tree_item->name = $item->name;
            $category_tree_item->id_parent = $parent;
            $category_tree_item->id_tree = $id_tree;
            $category_tree_item->code = empty($item->code) ? null : $item->code;
            $category_tree_item->sort = $sort;
            try {
                $category_tree_item->delete();
                $category_tree_item->create();
            } catch (Exception $e) {
                $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::_iterateCategoryTreeItems(): Error importing tree item ID ' . $item->id . ': '. $e->getMessage(), Writer::OUTPUT_FILE, 'import_error.log');
                $this->_errors[] = 'Importer::_iterateCategoryTreeItems(): Error importing tree item ID ' . $item->id . ': '. $e->getMessage();
            }
            if (isset($item->item)) {
                $this->_iterateCategoryTreeItems($id_tree, $item->item, $item->id);
            }
        }
    }

    /**
     * @param stdClass $media_object_data
     * @param integer $id_media_object
     * @return array
     * @throws Exception
     */
    private function _importMediaObjectData($media_object_data, $id_media_object)
    {
        $category_tree_ids = [];
        $conf =  Registry::getInstance()->get('config');
        $default_language = $conf['languages']['default'];
        $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::_importMediaObjectData(' . $id_media_object . '): Importing media object data', Writer::OUTPUT_FILE, 'import.log');
        $this->_current_media_object_data_to_import = [];
        $values = [];
        $ignore = [];
        foreach ($media_object_data->data as $datafield) {
            if (is_array($datafield->sections)) {
                foreach ($datafield->sections as $section) {
                    $var_name = HelperFunctions::human_to_machine($datafield->var_name);
                    if(!in_array($var_name, $ignore)) {
                        $column_name = $var_name . '_' . HelperFunctions::human_to_machine($section->name);
                        $language = empty($section->language) ? $default_language : $section->language;
                        if (!isset($values[$language])) $values[$language] = [];
                        $section_id = $section->id;
                        $value = null;
                        if($datafield->type == 'categorytree' && isset($datafield->value)) {
                            $value = $datafield->value;
                        } else if(isset($datafield->value) && isset($datafield->value->$section_id)) {
                            $value = $datafield->value->$section_id;
                        }
                        $values[$language]['language'] = $language;
                        $values[$language]['id_media_object'] = $media_object_data->id_media_object;
                        $values[$language][$column_name] = $value;
                    }
                }
            }
            if($datafield->type == 'categorytree' && isset($datafield->value) && isset($datafield->value->id_category)) {
                $category_tree_ids[] = $datafield->value->id_category;
            }
        }
        foreach ($values as $language => $section_data) {
            try {
                $media_object = Factory::createById($media_object_data->id_media_objects_data_type);
                $media_object->fromImport($section_data);
                $media_object->create();
                $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::_importMediaObjectData(' . $id_media_object . '): Object ' . get_class($media_object) . ' created with ID: ' . $media_object->getId(), Writer::OUTPUT_FILE, 'import.log');
                unset($media_object);
                unset($old_object);
            } catch (Exception $e) {
                $this->_log[] = $e->getMessage();
            }
        }
        unset($values);
        $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::_importMediaObjectData(' . $id_media_object . '): Heap cleaned up', Writer::OUTPUT_FILE, 'import.log');
        return $category_tree_ids;
    }

    /**
     * @throws Exception
     */
    public function postImport()
    {
        $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::postImport(): Starting post import processes ', Writer::OUTPUT_FILE, 'import.log');

        $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::postImport(): bash -c "exec nohup php ' . APPLICATION_PATH . '/cli/image_processor.php > /dev/null 2>&1 &"', Writer::OUTPUT_FILE, 'import.log');
        exec('bash -c "exec nohup php ' . APPLICATION_PATH . '/cli/image_processor.php > /dev/null 2>&1 &"');

        $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::postImport(): bash -c "exec nohup php ' . APPLICATION_PATH . '/cli/file_downloader.php > /dev/null 2>&1 &"', Writer::OUTPUT_FILE, 'import.log');
        exec('bash -c "exec nohup php ' . APPLICATION_PATH . '/cli/file_downloader.php > /dev/null 2>&1 &"');
    }

    /**
     * @param $ids
     * @throws Exception
     */
    public function importMediaObjectTypes($ids)
    {
        $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::importMediaObjectTypes(' . implode(',' ,$ids) . '): Starting import', Writer::OUTPUT_FILE, 'import.log');
        $response = $this->_client->sendRequest('ObjectType', 'getById', ['ids' => implode(',', $ids)]);
        $this->_checkApiResponse($response);
        foreach ($response->result as $result) {
            $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::importMediaObjectTypes(' . implode(',' ,$ids) . '): Starting scaffolding for ID: ' . $result->id, Writer::OUTPUT_FILE, 'import.log');
            $table_name = $result->id;
            $scaffolder = new ObjectTypeScaffolder($result, $table_name);
            $scaffolder->parse();
            if($scaffolder->hasErrors()) {
                echo ("WARNING: Importer::importMediaObjectTypes(" . implode(',' ,$ids) . ") threw errors:\n" . implode("\n", $scaffolder->getErrors())) . "\nSEE " . Writer::getLogFilePath() . DIRECTORY_SEPARATOR . "scaffolder_errors.log for details\n";
            }
            $this->_log[] = Writer::write($this->_getElapsedTimeAndHeap() . ' Importer::importMediaObjectTypes(' . implode(',' ,$ids) . '): Sacfolding for ID: ' . $result->id . ' finished', Writer::OUTPUT_FILE, 'import.log');
        }
    }

    private function _revertCurrentImport()
    {

    }

    /**
     * @return array
     */
    public function getLog()
    {
        return $this->_log;
    }

    public function hasErrors()
    {
        return count($this->_errors) > 0;
    }

    public function getErrors()
    {
        return $this->_errors;
    }

    /**
     * @return string
     */
    private function _getElapsedTimeAndHeap()
    {
        $text = number_format(microtime(true) - $this->_start_time, 4) . ' sec | Heap: ';
        $text .= bcdiv(memory_get_usage(), (1000 * 1000), 2) . ' MByte';
        return $text;
    }

    /**
     * @param $pResponse
     * @return bool
     * @throws Exception
     */
    private function _checkApiResponse($pResponse)
    {
        $error_msg = '';
        if (is_a($pResponse, 'stdClass') && isset($pResponse->result) && is_array($pResponse->result) && isset($pResponse->error) && $pResponse->error == false) {
            return true;
        }
        if(!isset($pResponse->result) || !isset($pResponse->error) || !isset($pResponse->msg) || !is_a($pResponse, 'stdClass')) {
            $error_msg = 'API response is not well formatted.';
        }
        if($pResponse->error == true) {
            $error_msg = $pResponse->msg;
        }
        throw new Exception($error_msg);
    }
}
