<?php
ini_set('display_errors', true);
error_reporting(1);
require_once dirname(__DIR__) . '/bootstrap.php';
$mediaObject = new \Pressmind\ORM\Object\MediaObject(intval($_GET['id']));
echo $mediaObject->render('example');
