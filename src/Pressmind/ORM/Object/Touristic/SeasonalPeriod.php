<?php

namespace Pressmind\ORM\Object\Touristic;

use Pressmind\ORM\Object\AbstractObject;
use \DateTime;

/**
 * Class SeasonalPeriod
 * @property integer $id
 * @property integer $id_media_object
 * @property integer $id_booking_package
 * @property DateTime $season_begin
 * @property DateTime $season_end
 * @property string $info
 * @property string $season
 * @property integer $monday
 * @property integer $tuesday
 * @property integer $wednesday
 * @property integer $thursday
 * @property integer $friday
 * @property integer $saturday
 * @property integer $sunday
 * @property integer $created_by
 * @property DateTime $created_date
 * @property integer $modified_by
 * @property DateTime $modified_date
 * @property integer $offset
 * @property integer $status
 * @property string $link
 * @property integer $pax_max
 * @property integer $pax_min
 * @property integer $pax
 * @property string $code_ibe
 * @property integer $id_touristic_early_birds
 * @property string $link_pib
 * @property string $code
 * @property integer $id_starting_point
 * @property integer $guaranteed
 * @property integer $saved
 * @property string $touroperator
 * @property Startingpoint $startingpoint
 */
class SeasonalPeriod extends AbstractObject
{

    protected $_dont_use_autoincrement_on_primary_key = true;

    protected $_definitions = array(
        'class' =>
            array(
                'name' => 'SeasonalPeriod',
            ),
        'database' =>
            array(
                'table_name' => 'pmt2core_touristic_seasonal_periods',
                'primary_key' => 'id',
            ),
        'properties' =>
            array(
                'id' =>
                    array(
                        'title' => 'Id',
                        'name' => 'id',
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
                'id_booking_package' =>
                    array(
                        'title' => 'Id_booking_package',
                        'name' => 'id_booking_package',
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
                'season_begin' =>
                    array(
                        'title' => 'Season_begin',
                        'name' => 'season_begin',
                        'type' => 'date',
                        'required' => true,
                        'validators' => NULL,
                        'filters' => NULL,
                    ),
                'season_end' =>
                    array(
                        'title' => 'Season_end',
                        'name' => 'season_end',
                        'type' => 'date',
                        'required' => true,
                        'validators' => NULL,
                        'filters' => NULL,
                    ),
                'info' =>
                    array(
                        'title' => 'Info',
                        'name' => 'info',
                        'type' => 'string',
                        'required' => false,
                        'validators' => NULL,
                        'filters' => NULL,
                    ),
                'season' =>
                    array(
                        'title' => 'Season',
                        'name' => 'season',
                        'type' => 'string',
                        'required' => true,
                        'validators' =>
                            array(
                                0 =>
                                    array(
                                        'name' => 'maxlength',
                                        'params' => 100,
                                    ),
                            ),
                        'filters' => NULL,
                    ),
                'monday' =>
                    array(
                        'title' => 'Monday',
                        'name' => 'monday',
                        'type' => 'boolean',
                        'required' => true,
                        'validators' =>
                            array(
                                0 =>
                                    array(
                                        'name' => 'maxlength',
                                        'params' => 1,
                                    ),
                            ),
                        'filters' => NULL,
                    ),
                'tuesday' =>
                    array(
                        'title' => 'Tuesday',
                        'name' => 'tuesday',
                        'type' => 'boolean',
                        'required' => true,
                        'validators' =>
                            array(
                                0 =>
                                    array(
                                        'name' => 'maxlength',
                                        'params' => 1,
                                    ),
                            ),
                        'filters' => NULL,
                    ),
                'wednesday' =>
                    array(
                        'title' => 'Wednesday',
                        'name' => 'wednesday',
                        'type' => 'boolean',
                        'required' => true,
                        'validators' =>
                            array(
                                0 =>
                                    array(
                                        'name' => 'maxlength',
                                        'params' => 1,
                                    ),
                            ),
                        'filters' => NULL,
                    ),
                'thursday' =>
                    array(
                        'title' => 'Thursday',
                        'name' => 'thursday',
                        'type' => 'boolean',
                        'required' => true,
                        'validators' =>
                            array(
                                0 =>
                                    array(
                                        'name' => 'maxlength',
                                        'params' => 1,
                                    ),
                            ),
                        'filters' => NULL,
                    ),
                'friday' =>
                    array(
                        'title' => 'Friday',
                        'name' => 'friday',
                        'type' => 'boolean',
                        'required' => true,
                        'validators' =>
                            array(
                                0 =>
                                    array(
                                        'name' => 'maxlength',
                                        'params' => 1,
                                    ),
                            ),
                        'filters' => NULL,
                    ),
                'saturday' =>
                    array(
                        'title' => 'Saturday',
                        'name' => 'saturday',
                        'type' => 'boolean',
                        'required' => true,
                        'validators' =>
                            array(
                                0 =>
                                    array(
                                        'name' => 'maxlength',
                                        'params' => 1,
                                    ),
                            ),
                        'filters' => NULL,
                    ),
                'sunday' =>
                    array(
                        'title' => 'Sunday',
                        'name' => 'sunday',
                        'type' => 'boolean',
                        'required' => true,
                        'validators' =>
                            array(
                                0 =>
                                    array(
                                        'name' => 'maxlength',
                                        'params' => 1,
                                    ),
                            ),
                        'filters' => NULL,
                    ),
                'offset' =>
                    array(
                        'title' => 'Offset',
                        'name' => 'offset',
                        'type' => 'integer',
                        'required' => false,
                        'validators' =>
                            array(
                                0 =>
                                    array(
                                        'name' => 'maxlength',
                                        'params' => 11,
                                    ),
                            ),
                        'filters' => NULL,
                    ),
                'state' =>
                    array(
                        'title' => 'Status',
                        'name' => 'state',
                        'type' => 'integer',
                        'required' => false,
                        'validators' =>
                            array(
                                0 =>
                                    array(
                                        'name' => 'maxlength',
                                        'params' => 1,
                                    ),
                            ),
                        'filters' => NULL,
                    ),
                'url' =>
                    array(
                        'title' => 'Url',
                        'name' => 'url',
                        'type' => 'string',
                        'required' => false,
                        'validators' =>
                            array(
                                0 =>
                                    array(
                                        'name' => 'maxlength',
                                        'params' => 255,
                                    ),
                            ),
                        'filters' => NULL,
                    ),
                'pax_max' =>
                    array(
                        'title' => 'Pax_max',
                        'name' => 'pax_max',
                        'type' => 'integer',
                        'required' => false,
                        'validators' =>
                            array(
                                0 =>
                                    array(
                                        'name' => 'maxlength',
                                        'params' => 11,
                                    ),
                            ),
                        'filters' => NULL,
                    ),
                'pax_min' =>
                    array(
                        'title' => 'Pax_min',
                        'name' => 'pax_min',
                        'type' => 'integer',
                        'required' => false,
                        'validators' =>
                            array(
                                0 =>
                                    array(
                                        'name' => 'maxlength',
                                        'params' => 11,
                                    ),
                            ),
                        'filters' => NULL,
                    ),
                'pax' =>
                    array(
                        'title' => 'Pax',
                        'name' => 'pax',
                        'type' => 'integer',
                        'required' => false,
                        'validators' =>
                            array(
                                0 =>
                                    array(
                                        'name' => 'maxlength',
                                        'params' => 11,
                                    ),
                            ),
                        'filters' => NULL,
                    ),
                'code_ibe' =>
                    array(
                        'title' => 'Code_ibe',
                        'name' => 'code_ibe',
                        'type' => 'string',
                        'required' => false,
                        'validators' =>
                            array(
                                0 =>
                                    array(
                                        'name' => 'maxlength',
                                        'params' => 255,
                                    ),
                            ),
                        'filters' => NULL,
                    ),
                'id_touristic_early_bird' =>
                    array(
                        'title' => 'Id_touristic_early_bird',
                        'name' => 'id_touristic_early_bird',
                        'type' => 'integer',
                        'required' => false,
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
                'link_pib' =>
                    array(
                        'title' => 'Link_pib',
                        'name' => 'link_pib',
                        'type' => 'string',
                        'required' => false,
                        'validators' => NULL,
                        'filters' => NULL,
                    ),
                'code' =>
                    array(
                        'title' => 'Code',
                        'name' => 'code',
                        'type' => 'string',
                        'required' => false,
                        'validators' =>
                            array(
                                0 =>
                                    array(
                                        'name' => 'maxlength',
                                        'params' => 255,
                                    ),
                            ),
                        'filters' => NULL,
                    ),
                'id_starting_point' =>
                    array(
                        'title' => 'Id_starting_point',
                        'name' => 'id_starting_point',
                        'type' => 'integer',
                        'required' => false,
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
                'guaranteed' =>
                    array(
                        'title' => 'Guaranteed',
                        'name' => 'guaranteed',
                        'type' => 'integer',
                        'required' => false,
                        'validators' =>
                            array(
                                0 =>
                                    array(
                                        'name' => 'maxlength',
                                        'params' => 1,
                                    ),
                            ),
                        'filters' => NULL,
                    ),
                'saved' =>
                    array(
                        'title' => 'Saved',
                        'name' => 'saved',
                        'type' => 'integer',
                        'required' => false,
                        'validators' =>
                            array(
                                0 =>
                                    array(
                                        'name' => 'maxlength',
                                        'params' => 1,
                                    ),
                            ),
                        'filters' => NULL,
                    ),
                'touroperator' =>
                    array(
                        'title' => 'Touroperator',
                        'name' => 'touroperator',
                        'type' => 'string',
                        'required' => false,
                        'validators' =>
                            array(
                                0 =>
                                    array(
                                        'name' => 'maxlength',
                                        'params' => 32,
                                    ),
                            ),
                        'filters' => NULL,
                    ),
                'startingpoint' => array(
                    'title' => 'Startingpoint',
                    'name' => 'startingpoint',
                    'type' => 'relation',
                    'relation' => array(
                        'type' => 'hasOne',
                        'related_id' => 'id_starting_point',
                        'class' => '\\Pressmind\\ORM\\Object\\Touristic\\Startingpoint'
                    ),
                    'required' => false,
                    'validators' => null,
                    'filters' => null
                ),
            ),
    );
}
