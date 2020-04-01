<?php


namespace Pressmind\Image;


class Download
{
    public function __construct()
    {

    }

    public function download($url, $targetPath, $targetName)
    {
        $ch = curl_init ($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $raw=curl_exec($ch);
        curl_close ($ch);
        if(file_exists($targetPath . DIRECTORY_SEPARATOR . $targetName)){
            unlink($targetPath . DIRECTORY_SEPARATOR . $targetName);
        }
        $fp = fopen($targetPath . DIRECTORY_SEPARATOR . $targetName,'w');
        fwrite($fp, $raw);
        fclose($fp);
        if(substr($targetName, -3) == 'pdf') {
            $newname = $targetName;
        } else {
            $pathinfo = pathinfo($targetPath . DIRECTORY_SEPARATOR . $targetName);
            $imageinfo = getimagesize($targetPath . DIRECTORY_SEPARATOR . $targetName);
            $extension = image_type_to_extension($imageinfo[2]);
            $newname = $pathinfo['filename'] . $extension;
        }
        rename($targetPath . DIRECTORY_SEPARATOR . $targetName, $targetPath . DIRECTORY_SEPARATOR . $newname);
        return $newname;
    }
}
