<?php


namespace Pressmind\Image\Processor\Adapter;


use Imagick;
use ImagickException;
use Pressmind\Image\Processor\AdapterInterface;
use Pressmind\Image\Processor\Config;

class ImageMagick implements AdapterInterface
{

    private $_image;

    /**
     * @param Config $config
     * @param string $file
     * @param string $derivative_name
     * @return mixed|Config
     * @throws ImagickException
     */
    public function process($config, $file, $derivative_name)
    {
        $this->_image = new Imagick($file);
        if($config->crop == true) {
            $this->_image->cropThumbnailImage($config->max_width,$config->max_height);
        } else {
            $this->_image->thumbnailImage($config->max_width, $config->max_height, $config->preserve_aspect_ratio);
        }
        $path_info = pathinfo($file);
        $path = $path_info['dirname'];
        $new_name = $path_info['filename'] . '_' . $derivative_name . '.' . $path_info['extension'];
        $this->_image->writeImage($path . DIRECTORY_SEPARATOR . $new_name);
        return $path . DIRECTORY_SEPARATOR . $new_name;
    }
}
