<?php


namespace Pressmind\Image\Processor;


class Config
{
    public $max_width;
    public $max_height;
    public $preserve_aspect_ratio;
    public $crop;
    public $horizontal_crop;
    public $vertical_crop;

    public function __construct()
    {

    }

    public static function create($array)
    {
        $config = new self();
        $config->max_width = isset($array['max_width']) ? $array['max_width'] : null;
        $config->max_height = isset($array['max_height']) ? $array['max_height'] : null;
        $config->preserve_aspect_ratio = isset($array['preserve_aspect_ratio']) ? $array['preserve_aspect_ratio'] : null;
        $config->crop = isset($array['crop']) ? $array['crop'] : null;
        $config->horizontal_crop = isset($array['horizontal_crop']) ? $array['horizontal_crop'] : null;
        $config->vertical_crop = isset($array['vertical_crop']) ? $array['vertical_crop'] : null;
        return $config;
    }
}
