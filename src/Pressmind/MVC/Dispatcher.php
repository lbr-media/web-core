<?php


namespace Pressmind\MVC;


use Pressmind\HelperFunctions;
use Pressmind\Registry;

class Dispatcher
{
    /**
     * @var Router
     */
    private $_router;

    /**
     * @var Request
     */
    private $_request;

    /**
     * @var Response
     */
    private $_response;

    private static $_instance = null;

    private $_layout_enabled = true;

    public function __construct()
    {
        $response = new Response();
        $response->setContentType('text/html');
        $this->_response = $response;
    }

    public static function getInstance()
    {
        if(is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * @param Request $request
     */
    public function setRequest($request)
    {
        $this->_request = $request;
    }

    /**
     * @param Response $response
     */
    public function setResponse($response)
    {
        $this->_response = $response;
    }

    /**
     * @param Router $router
     */
    public function setRouter($router)
    {
        $this->_router = $router;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->_request;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->_response;
    }

    /**
     * @return Router
     */
    public function getRouter()
    {
        return $this->_router;
    }

    public function disableLayout()
    {
        $this->_layout_enabled = false;
    }

    public function dispatch()
    {
        $config = Registry::getInstance()->get('config');
        /** @var AbstractController $controller */
        $executionStartTime = microtime(true);
        $result = $this->_router->handle($this->_request);
        $class_name = '\\Application\\' . ucfirst($result['module']) . '\\Controller\\' . ucfirst($result['controller']);
        $method_name = $result['action'];
        $controller = new $class_name($result);
        $controller->init();
        $content = $controller->$method_name();
        $layout_content = '';
        $layout_script = HelperFunctions::buildPathString(
            [
                APPLICATION_PATH,
                ucfirst($result['module']),
                'View',
                'Layout'
            ]
        );
        if($this->_layout_enabled == true) {
            if (isset($config['layout']['external']) && !empty($config['layout']['external']) && !file_exists($layout_script . '.php')) {
                $external_html = file_get_contents($config['layout']['external']);
                $layout_content = str_replace(
                    [
                        '###PRESSMIND_IBE_CONTENT###',
                        '###PRESSMIND_IBE_HEADER_CSS###',
                        '###PRESSMIND_IBE_HEADER_SCRIPTS###',
                        '###PRESSMIND_IBE_FOOTER_SCRIPTS###'
                    ],
                    [
                        $content,
                        $controller->renderCssStyleIncludes(),
                        $controller->renderHeaderScriptIncludes() . $controller->renderHeaderScripts(),
                        $controller->renderFooterScriptIncludes()
                    ],
                    $external_html
                );
            } else {
                $layout = new View();
                $layout->setViewScript(HelperFunctions::buildPathString([
                    ucfirst($result['module']),
                    'View',
                    'Layout'
                ]));
                $layout_content = $layout->render(
                    [
                        'content' => $content,
                        'headerScriptIncludes' => $controller->renderHeaderScriptIncludes(),
                        'footerScriptIncludes' => $controller->renderFooterScriptIncludes(),
                        'cssStyleIncludes' => $controller->renderCssStyleIncludes(),
                        'executionTime' => microtime(true) - $executionStartTime
                    ]
                );
            }
        } else {
            $layout_content = $content;
        }
        $this->_response->setBody($layout_content);
        $this->_response->setCode(200);
        $this->_response->send();
    }
}
