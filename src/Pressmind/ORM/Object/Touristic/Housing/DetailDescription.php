<?php

namespace Pressmind\ORM\Object\Touristic\Housing;

use Pressmind\ORM\Object\AbstractObject;

/**
 * Class TouristicHousingDetailDescription
 * @property integer $id_housing_package
 * @property integer $id_media_object
 * @property integer $id_media_object_housing_description
 */
class DetailDescription extends AbstractObject
{
    protected $_definitions = array(
        'class' =>
            array(
                'name' => 'TouristicHousingDetailDescription',
            ),
        'database' =>
            array(
                'table_name' => 'pmt2core_touristic_housing_detail_descriptions',
                'primary_key' => NULL,
            ),
        'properties' =>
            array(
                'id_housing_package' =>
                    array(
                        'title' => 'Id_housing_package',
                        'name' => 'id_housing_package',
                        'type' => 'integer',
                        'required' => true,
                        'validators' =>
                            array(
                                0 =>
                                    array(
                                        'name' => 'maxlength',
                                        'params' => 22,
                                    ),
                            ),
                        'filters' => NULL,
                    ),
                'id_media_object' =>
                    array(
                        'title' => 'Id_media_object',
                        'name' => 'id_media_object',
                        'type' => 'integer',
                        'required' => true,
                        'validators' =>
                            array(
                                0 =>
                                    array(
                                        'name' => 'maxlength',
                                        'params' => 22,
                                    ),
                            ),
                        'filters' => NULL,
                    ),
                'id_media_object_housing_description' =>
                    array(
                        'title' => 'Id_media_object_housing_description',
                        'name' => 'id_media_object_housing_description',
                        'type' => 'integer',
                        'required' => true,
                        'validators' =>
                            array(
                                0 =>
                                    array(
                                        'name' => 'maxlength',
                                        'params' => 22,
                                    ),
                            ),
                        'filters' => NULL,
                    ),
            ),
    );
}
