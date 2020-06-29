<?php


namespace Pressmind\Search\Condition;


use ReflectionClass;
use ReflectionException;

class Visibility implements ConditionInterface
{
    /**
     * @var array
     */
    private $_visibilities = [];

    /**
     * @var array
     */
    private $_values = [];

    /**
     * @var int
     */
    private $_sort = 6;

    /**
     * Category constructor.
     * @param $pVisibilities
     */
    public function __construct($pVisibilities)
    {
        $this->_visibilities = $pVisibilities;
    }

    public function getSQL()
    {
        $visibility_strings = [];
        $term_counter = 0;
        foreach ($this->_visibilities as $visibility) {
            $term_counter++;
            $visibility_strings[] = 'pmt2core_media_objects.visibility = :visibility' . $term_counter;
            $this->_values[':visibility' . $term_counter] = $visibility;
        }
        $sql = "(" . implode(' OR ', $visibility_strings) . ")";
        return $sql;
    }

    public function getSort()
    {
        return $this->_sort;
    }

    public function getValues()
    {
        return $this->_values;
    }

    public static function create($pVisibilities)
    {
        $object = new self($pVisibilities);
        return $object;
    }

    public function getJoins()
    {
        return null;
    }

    public function getAdditionalFields()
    {
        return null;
    }

    public function setConfig($config)
    {
        $this->_visibilities = $config->_visibilities;
    }

    /**
     * @return array
     */
    public function getConfig() {
        return [
            'visibilities' => $this->_visibilities,
        ];
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    public function toJson() {
        $data = [
            'type' => (new ReflectionClass($this))->getShortName(),
            'config' => $this->getConfig()
        ];
        return $data;
    }
}
