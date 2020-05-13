<?php


namespace Pressmind\Import\Mapper;


use Pressmind\ORM\Object\MediaObject\DataType\Key_value\Row;
use stdClass;

class Key_value implements MapperInterface
{
    public function map($pIdMediaObject,$pLanguage, $pVarName, $pObject)
    {
        if(!is_null($pObject) && count($pObject['values']) > 0) {
            $mapped_object = new stdClass();
            $mapped_object->id = null;
            $mapped_object->id_media_object = $pIdMediaObject;
            $mapped_object->section_name = '';
            $mapped_object->language = $pLanguage;
            $mapped_object->var_name = $pVarName;
            $key_value_rows = [];
            $tmp_columns = [];
            foreach ($pObject['columns'] as $column) {
                $tmp_columns[$column->sort] = $column;
            }
            ksort($tmp_columns);
            foreach ($pObject['values'] as $row) {
                $key_value_row = new Row();
                $key_value_row->sort = $row->sort;
                $key_value_row_columns = [];
                foreach ($tmp_columns as $key => $tmp_column) {
                    $key_value_row_column = new Row\Column();
                    $key_value_row_column->sort = $tmp_column->sort;
                    $key_value_row_column->title = $tmp_column->name;
                    $key_value_row_column->var_name = $tmp_column->var_name;
                    switch($tmp_column->type) {
                        case 'PLAINTEXT':
                        case 'DROPDOWN':
                        default:
                            $value_name = 'value_' . $key . '_string';
                            $key_value_row_column->value_string = $row->$value_name;
                            $key_value_row_column->datatype = 'string';
                            break;
                        case 'INTEGER':
                            $value_name = 'value_' . $key . '_int';
                            $key_value_row_column->value_integer = $row->$value_name;
                            $key_value_row_column->datatype = 'integer';
                            break;
                        case 'NUMBER':
                            $value_name = 'value_' . $key . '_decimal';
                            $key_value_row_column->value_float = $row->$value_name;
                            $key_value_row_column->datatype = 'float';
                            break;
                    }
                    $key_value_row_columns[] = $key_value_row_column->toStdClass();
                }
                $key_value_row->columns = $key_value_row_columns;
                $key_value_rows[] = $key_value_row->toStdClass();
                $mapped_object->rows = $key_value_rows;
            }
            return [$mapped_object];
        } else {
            return [];
        }
    }
}
