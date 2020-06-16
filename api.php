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
