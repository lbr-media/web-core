<?php
namespace Pressmind;

use Pressmind\MVC\Request;
use Pressmind\MVC\Response;
use Pressmind\REST\Controller\Ibe;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'bootstrap.php';

$request = new Request();

if($request->isPost()) {
    $response = new Response();
    $response->setContentType('application/json');
    try {
        $action = $request->getParameter('action');
        $class = new Ibe($request->getParameter('data'));
        $response->setBody(json_encode(['success' => true, 'data' => $class->$action()]));
    } catch (\Exception $e) {
        $response->setCode(500);
        $response->setBody(json_encode(['success' => false, 'msg' => $e->getMessage()]));
    }
    $response->send();
}

if($request->isGet()) {
    if($request->getParameter('type') == 'import') {
        $response = new Response();
        $response->setContentType('application/json');
        try {
            $importer = new Import();
            $importer->importMediaObject($request->getParameter('id_media_object'));
            $importer->postImportImageProcessor($request->getParameter('id_media_object'));
            if($request->getParameter('preview') == "1") {
                $media_object = new ORM\Object\MediaObject($request->getParameter('id_media_object'));
                $config = Registry::getInstance()->get('config');
                $preview_url = WEBSERVER_HTTP . str_replace(['{{id_media_object}}', '{{preview}}'], [$media_object->getId(), '1'], $config['data']['preview_url']);
                $response->setContentType('text/html');
                $response->setBody('You will be redirected to Preview Page: ' . $preview_url);
                $response->addHeader('Location', $preview_url);
            } else {
                $response->setBody(json_encode(['status' => 'Code 200: Import erfolgreich', 'url' => null, 'msg' => implode("\n", $importer->getLog())]));
            }
        } catch (\Exception $e) {
            $response->setCode(500);
            $response->setBody(json_encode(['status' => 'Code 500: Es ist ein Fehler aufgetreten', 'msg' => $e->getMessage()]));
        }
        $response->send();
    }
}
