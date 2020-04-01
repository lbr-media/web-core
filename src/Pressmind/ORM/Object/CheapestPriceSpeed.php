<?php


namespace Pressmind\ORM\Object;

use DateTime;

/**
 * Class CheapestPriceSpeed
 * @package Pressmind\ORM\Object
 * @property integer $id_media_object
 * @property integer $id_booking_package
 * @property integer $id_housing_package
 * @property integer $id_date
 * @property integer $id_option
 * @property integer $id_transport_1
 * @property integer $id_transport_2
 * @property integer $duration
 * @property DateTime $date_departure
 * @property DateTime $date_arrival
 * @property string $option_name
 * @property string $option_code
 * @property string $option_board_type
 * @property integer $option_occupancy
 * @property integer $option_occupancy_min
 * @property integer $option_occupancy_max
 * @property float $price_transport_total
 * @property float $price_transport_1
 * @property float $price_transport_2
 * @property string $price_mix
 * @property float $price_option
 * @property float $price_option_pseudo
 * @property float $price_regular_before_discount
 * @property float $price_total
 * @property string $transport_code
 * @property string $transport_type
 * @property integer $transport_1_way
 * @property integer $transport_2_way
 * @property string $transport_1_description
 * @property string $transport_2_description
 * @property integer $state
 * @property string $infotext
 * @property float $earlybird_discount
 * @property DateTime $earlybird_discount_date_to
 * @property float $earlybird_discount_f
 * @property DateTime $earlybird_discount_date_to_f
 * @property integer $id_option_auto_book
 * @property integer $id_option_required_group
 * @property integer $id_start_point_option
 * @property integer $id_origin
 * @property integer $id_startingpoint
 */
class CheapestPriceSpeed extends AbstractObject
{

    protected $_definitions = [
        'class' => [
            'name' => 'CheapestPriceSpeed',
            'namespace' => '\Pressmind\ORM\Object'
        ],
        'database' => [
            'table_name' => 'pmt2core_cheapest_price_speed',
            'primary_key' => 'id',
        ],
        'properties' => [
            'id' => [
                'name' => 'id',
                'title' => 'id',
                'type' => 'integer',
                'required' => true,
                'filters' => null,
                'validators' => null
            ],
            'id_media_object' => [
                'name' => 'id_media_object',
                'title' => 'id_media_object',
                'type' => 'integer',
                'required' => false,
                'filters' => null,
                'validators' => null
            ],
            'id_booking_package' => [
                'name' => 'id_booking_package',
                'title' => 'id_booking_package',
                'type' => 'integer',
                'required' => false,
                'filters' => null,
                'validators' => null
            ],
            'id_housing_package' => [
                'name' => 'id_housing_package',
                'title' => 'id_housing_package',
                'type' => 'integer',
                'required' => false,
                'filters' => null,
                'validators' => null
            ],
            'id_date' => [
                'name' => 'id_date',
                'title' => 'id_date',
                'type' => 'integer',
                'required' => false,
                'filters' => null,
                'validators' => null
            ],
            'id_option' => [
                'name' => 'id_option',
                'title' => 'id_option',
                'type' => 'integer',
                'required' => false,
                'filters' => null,
                'validators' => null
            ],
            'id_transport_1' => [
                'name' => 'id_transport_1',
                'title' => 'id_transport_1',
                'type' => 'integer',
                'required' => false,
                'filters' => null,
                'validators' => null
            ],
            'id_transport_2' => [
                'name' => 'id_transport_2',
                'title' => 'id_transport_2',
                'type' => 'integer',
                'required' => false,
                'filters' => null,
                'validators' => null
            ],
            'duration' => [
                'name' => 'duration',
                'title' => 'duration',
                'type' => 'integer',
                'required' => false,
                'filters' => null,
                'validators' => null
            ],
            'date_departure' => [
                'name' => 'date_departure',
                'title' => 'date_departure',
                'type' => 'datetime',
                'required' => false,
                'filters' => null,
                'validators' => null
            ],
            'date_arrival' => [
                'name' => 'date_arrival',
                'title' => 'date_arrival',
                'type' => 'datetime',
                'required' => false,
                'filters' => null,
                'validators' => null
            ],
            'option_name' => [
                'name' => 'option_name',
                'title' => 'option_name',
                'type' => 'string',
                'required' => false,
                'filters' => null,
                'validators' => null
            ],
            'option_code' => [
                'name' => 'option_code',
                'title' => 'option_code',
                'type' => 'string',
                'required' => false,
                'filters' => null,
                'validators' => null
            ],
            'option_board_type' => [
                'name' => 'option_board_type',
                'title' => 'option_board_type',
                'type' => 'string',
                'required' => false,
                'filters' => null,
                'validators' => null
            ],
            'option_occupancy' => [
                'name' => 'option_occupancy',
                'title' => 'option_occupancy',
                'type' => 'integer',
                'required' => false,
                'filters' => null,
                'validators' => null
            ],
            'option_occupancy_min' => [
                'name' => 'option_occupancy_min',
                'title' => 'option_occupancy_min',
                'type' => 'integer',
                'required' => false,
                'filters' => null,
                'validators' => null
            ],
            'option_occupancy_max' => [
                'name' => 'option_occupancy_max',
                'title' => 'option_occupancy_max',
                'type' => 'integer',
                'required' => false,
                'filters' => null,
                'validators' => null
            ],
            'price_transport_total' => [
                'name' => 'price_transport_total',
                'title' => 'price_transport_total',
                'type' => 'float',
                'required' => false,
                'filters' => null,
                'validators' => null
            ],
            'price_transport_1' => [
                'name' => 'price_transport_1',
                'title' => 'price_transport_1',
                'type' => 'float',
                'required' => false,
                'filters' => null,
                'validators' => null
            ],
            'price_transport_2' => [
                'name' => 'price_transport_2',
                'title' => 'price_transport_2',
                'type' => 'float',
                'required' => false,
                'filters' => null,
                'validators' => null
            ],
            'price_mix' => [
                'name' => 'price_mix',
                'title' => 'price_mix',
                'type' => 'string',
                'required' => false,
                'filters' => null,
                'validators' => null
            ],
            'price_option' => [
                'name' => 'price_option',
                'title' => 'price_option',
                'type' => 'float',
                'required' => false,
                'filters' => null,
                'validators' => null
            ],
            'price_option_pseudo' => [
                'name' => 'price_option_pseudo',
                'title' => 'price_option_pseudo',
                'type' => 'float',
                'required' => false,
                'filters' => null,
                'validators' => null
            ],
            'price_regular_before_discount' => [
                'name' => 'price_regular_before_discount',
                'title' => 'price_regular_before_discount',
                'type' => 'float',
                'required' => false,
                'filters' => null,
                'validators' => null
            ],
            'price_total' => [
                'name' => 'price_total',
                'title' => 'price_total',
                'type' => 'float',
                'required' => false,
                'filters' => null,
                'validators' => null
            ],
            'transport_code' => [
                'name' => 'transport_code',
                'title' => 'transport_code',
                'type' => 'string',
                'required' => false,
                'filters' => null,
                'validators' => null
            ],
            'transport_type' => [
                'name' => 'transport_type',
                'title' => 'transport_type',
                'type' => 'string',
                'required' => false,
                'filters' => null,
                'validators' => null
            ],
            'transport_1_way' => [
                'name' => 'transport_1_way',
                'title' => 'transport_1_way',
                'type' => 'integer',
                'required' => false,
                'filters' => null,
                'validators' => null
            ],
            'transport_2_way' => [
                'name' => 'transport_2_way',
                'title' => 'transport_2_way',
                'type' => 'integer',
                'required' => false,
                'filters' => null,
                'validators' => null
            ],
            'transport_1_description' => [
                'name' => 'transport_1_description',
                'title' => 'transport_1_description',
                'type' => 'string',
                'required' => false,
                'filters' => null,
                'validators' => null
            ],
            'transport_2_description' => [
                'name' => 'transport_2_description',
                'title' => 'transport_2_description',
                'type' => 'string',
                'required' => false,
                'filters' => null,
                'validators' => null
            ],
            'state' => [
                'name' => 'state',
                'title' => 'state',
                'type' => 'integer',
                'required' => false,
                'filters' => null,
                'validators' => null
            ],
            'infotext' => [
                'name' => 'infotext',
                'title' => 'infotext',
                'type' => 'string',
                'required' => false,
                'filters' => null,
                'validators' => null
            ],
            'earlybird_discount' => [
                'name' => 'earlybird_discount',
                'title' => 'earlybird_discount',
                'type' => 'float',
                'required' => false,
                'filters' => null,
                'validators' => null
            ],
            'earlybird_discount_date_to' => [
                'name' => 'earlybird_discount_date_to',
                'title' => 'earlybird_discount_date_to',
                'type' => 'DateTime',
                'required' => false,
                'filters' => null,
                'validators' => null
            ],
            'earlybird_discount_f' => [
                'name' => 'earlybird_discount_f',
                'title' => 'earlybird_discount_f',
                'type' => 'float',
                'required' => false,
                'filters' => null,
                'validators' => null
            ],
            'earlybird_discount_date_to_f' => [
                'name' => 'earlybird_discount_date_to_f',
                'title' => 'earlybird_discount_date_to_f',
                'type' => 'DateTime',
                'required' => false,
                'filters' => null,
                'validators' => null
            ],
            'id_option_auto_book' => [
                'name' => 'id_option_auto_book',
                'title' => 'id_option_auto_book',
                'type' => 'integer',
                'required' => false,
                'filters' => null,
                'validators' => null
            ],
            'id_option_required_group' => [
                'name' => 'id_option_required_group',
                'title' => 'id_option_required_group',
                'type' => 'integer',
                'required' => false,
                'filters' => null,
                'validators' => null
            ],
            'id_start_point_option' => [
                'name' => 'id_start_point_option',
                'title' => 'id_start_point_option',
                'type' => 'integer',
                'required' => false,
                'filters' => null,
                'validators' => null
            ],
            'id_origin' => [
                'name' => 'id_origin',
                'title' => 'id_origin',
                'type' => 'integer',
                'required' => false,
                'filters' => null,
                'validators' => null
            ],
            'id_startingpoint' => [
                'name' => 'id_startingpoint',
                'title' => 'id_startingpoint',
                'type' => 'integer',
                'required' => false,
                'filters' => null,
                'validators' => null
            ]
        ]
    ];
}
