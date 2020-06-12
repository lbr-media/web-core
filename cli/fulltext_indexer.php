<?php
namespace Pressmind;

use Pressmind\ORM\Object\MediaObject;

if(php_sapi_name() == 'cli') {
    putenv('ENV=DEVELOPMENT');
}

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';

/**
 * @var DB\Adapter\Pdo $db
 */
$db = Registry::getInstance()->get('db');

/**@var \Pressmind\ORM\Object\MediaObject[] $media_objects**/
$media_objects = MediaObject::listAll();

$fulltext = [];

$db->execute('TRUNCATE pmt2core_fulltext_search');

foreach ($media_objects as $media_object) {
    $fulltext[] = [
        'var_name' => 'code',
        'id_media_object' => $media_object->getId(),
        'fulltext_values' => $media_object->code
    ];
    $fulltext[] = [
        'var_name' => 'name',
        'id_media_object' => $media_object->getId(),
        'fulltext_values' => $media_object->name
    ];
    $fulltext[] = [
        'var_name' => 'tags',
        'id_media_object' => $media_object->getId(),
        'fulltext_values' => $media_object->tags
    ];
    foreach ($media_object->data as $data) {
        foreach($data->getPropertyDefinitions() as $name => $definition) {
            if($definition['type'] == 'string') {
                $fulltext[] = [
                    'var_name' => $name,
                    'id_media_object' => $media_object->getId(),
                    'fulltext_values' => preg_replace('/\s+/', ' ', strip_tags(str_replace('>', '> ', $data->$name)))
                ];
            }
            if($definition['type'] == 'relation') {
                $values = [];
                if($definition['relation']['class'] == '\\Pressmind\\ORM\\Object\\MediaObject\\DataType\\Categorytree') {
                    foreach ($data->$name as $tree) {
                        $values[] = $tree->item->name;
                    }
                }
                if(count($values) > 0) {
                    $fulltext[] = [
                        'var_name' => $name,
                        'id_media_object' => $media_object->getId(),
                        'fulltext_values' => implode(' ', $values)
                    ];
                }
            }
        }
    }
}
foreach ($fulltext as $fulltext_data) {
    $db->insert('pmt2core_fulltext_search', $fulltext_data);
}
