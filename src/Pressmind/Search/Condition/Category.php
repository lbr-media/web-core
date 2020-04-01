<?php


namespace Pressmind\Search\Condition;


class Category implements ConditionInterface
{

    /**
     * @var string
     */
    private $_var_name;

    /**
     * @var array
     */
    private $_item_ids = [];

    /**
     * @var array
     */
    private $_values = [];

    /**
     * @var int
     */
    private $_sort = 3;

    /**
     * Category constructor.
     * @param $pVarName
     * @param $pItemIds
     */
    public function __construct($pVarName = null, $pItemIds = null)
    {
        $this->_var_name = $pVarName;
        $this->_item_ids = $pItemIds;
    }

    public function getSQL()
    {
        $item_id_strings = [];
        $term_counter = 0;
        foreach ($this->_item_ids as $item_id) {
            $term_counter++;
            $item_id_strings[] = $this->_var_name . '.id_item = :' . $this->_var_name . $term_counter;
            $this->_values[':' . $this->_var_name . $term_counter] = $item_id;
        }
        $sql = $this->_var_name . ".var_name = '" . $this->_var_name . "' AND (" . implode(' OR ', $item_id_strings) . ")";
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

    public function getJoins()
    {
        return 'INNER JOIN pmt2core_media_object_tree_items ' . $this->_var_name . ' ON pmt2core_media_objects.id = ' . $this->_var_name . '.id_media_object';
    }

    public function getAdditionalFields()
    {
        return null;
    }

    public static function create($pVarName, $pItemIds)
    {
        $object = new self($pVarName, $pItemIds);
        return $object;
    }

    /**
     * @param \stdClass $config
     */
    public function setConfig($config) {
        $this->_var_name = $config->var_name;
        $this->_item_ids = $config->item_ids;
    }
}
