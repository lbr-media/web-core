<?php
/**
 * Created by PhpStorm.
 * User: m.graute
 * Date: 20.08.2019
 * Time: 10:29
 */

namespace Pressmind\REST;
use \stdClass;
use \Exception;
/**
 * Class Server
 * @package Pressmind\REST
 * @link http://pmt2core/docs/classes/Pressmind.Rest.Server.html
 */
class Server
{
    /**
     * @var string
     */
    private $_request_method;

    /**
     * @var array|false
     */
    private $_request_headers;

    /**
     * @var string
     */
    private $_raw_request_body;

    /**
     * @var stdClass
     */
    private $_request_body;

    /**
     * @var array
     */
    private $_request;

    /**
     * @var mixed
     */
    private $_response;

    /**
     * @var string
     */
    private $_module;

    /**
     * @var string
     */
    private $_controller;

    /**
     * @var string
     */
    private $_action;

    /**
     * @var array
     */
    private $_parameters = [];

    /**
     * @var array
     */
    private $_headers = [];

    /**
     * @var array
     */
    private $_output_methods = ['GET', 'PUT', 'DELETE', 'POST'];

    /**
     * @var array
     */
    private $_header_methods = ['OPTIONS', 'HEAD'];

    /**
     * @var boolean
     */
    private $_success = true;


    public function __construct($pApiBaseUrl = null)
    {
        $this->_addHeader('Access-Control-Allow-Origin: *');
        $this->_addHeader('Access-Control-Allow-Headers: *');
        $this->_request_method = $_SERVER['REQUEST_METHOD'];
        $this->_request_headers = $this->_apache_request_headers();
        if(in_array($this->_request_method, $this->_output_methods)) { //We don't need to parse uri and body for OPTIONS and HEAD
            $this->_parseRequestUri($pApiBaseUrl);
            $this->_parseRequestBody();
        }
    }

    private function _parseRequestUri($pApiBaseUrl) {
        $request_uri = $_SERVER['REQUEST_URI'];
        /**We need to make sure that only module, controller, action and parameters are in the url string**/
        if(!empty($ApiBaseUrl)) {
            $pos = strpos($request_uri, $pApiBaseUrl);
            if ($pos !== false) {
                $request_uri = substr_replace($request_uri, '', $pos, strlen($pApiBaseUrl));
            }
        }
        $this->_request = explode('/', trim($request_uri, '/'));
        $this->_module = $this->_request[0];
        $this->_controller = $this->_request[1];
        $this->_action = $this->_request[2];
        if (count($this->_request) > 3) {
            $this->_parameters = $this->_parseParameters(array_slice($this->_request, 4));
        }

    }

    private function _apache_request_headers()
{
        if(function_exists('apache_request_headers')) {
            return apache_request_headers();
        }
        $arh = [];
        $rx_http = '/\AHTTP_/';
        foreach($_SERVER as $key => $val) {
            if( preg_match($rx_http, $key) ) {
                $arh_key = preg_replace($rx_http, '', $key);
                // do some nasty string manipulations to restore the original letter case
                // this should work in most cases
                $rx_matches = explode('_', $arh_key);
                if( count($rx_matches) > 0 and strlen($arh_key) > 2 ) {
                    foreach($rx_matches as $ak_key => $ak_val) $rx_matches[$ak_key] = ucfirst(strtolower($ak_val));
                    $arh_key = implode('-', $rx_matches);
                }
                $arh[$arh_key] = $val;
            }
        }
        return( $arh );
    }

    public function handle()
    {
        $this->_response = $this->_handleMethod();
        foreach ($this->_headers as $header) {
            header($header);
        }
        if(isset($this->_request_headers['content-length']) && strlen($this->_raw_request_body) != $this->_request_headers['content-length'] && ($this->_request_method == 'POST' || $this->_request_method == 'PUT')) {
            echo json_encode(['success' => false, 'error' => array('code' => '500', 'message' => 'Header "content-length" is not equal to length of request body')]);
        } else {
            if (!in_array($this->_request_method, $this->_header_methods)) {
                $return = array(
                    'success' => $this->_success,
                );
                if (defined('DEBUG') && DEBUG === true) {
                    $return['request'] = array(
                        'module' => $this->_module,
                        'controller' => $this->_controller,
                        'function' => $this->_action,
                        'parameters' => $this->_parameters,
                        'body' => $this->_request_body,
                        'headers' => $this->_request_headers
                    );
                }
                $return['data'] = $this->_response;
                echo json_encode(
                    $return
                );
            }
        }
    }

    private function _getBearerToken()
{
        list($token) = sscanf($this->_request_headers['Authorization'], 'Bearer %s');
        return $token;
    }

    private function _addHeader($pHeader) {
        $this->_headers[] = $pHeader;
    }

    private function _parseParameters($pArray)
    {
        $keys = [];
        $values = [];
        foreach ($pArray as $index => $val) {
            if($index % 2 == 0) {
                $keys[] = $val;
            } else {
                if(empty($val)) $val = null;
                $values[] = $val;
            }
        }
        if(count($pArray) % 2 == 1) $values[] = null;
        return array_combine($keys, $values);
    }

    private function _handleMethod()
    {
        switch($this->_request_method) {
            case 'GET':
                return $this->_handleGet();
            case 'POST':
                return $this->_handlePost();
            case 'PUT':
                return $this->_handlePut();
            case 'DELETE':
                return $this->_handleDelete();
            case 'OPTIONS':
                return $this->_handleOptions();
            case 'HEAD':
                return $this->_handleHead();
            default:
                return $this->_handleMethodError();
        }
    }

    private function _parseRequestBody()
    {
        if($this->_request_method == 'POST' || $this->_request_method == 'PUT') {
            $this->_raw_request_body = file_get_contents('php://input');
            $this->_request_body = json_decode($this->_raw_request_body);
        }
    }

    private function _handleGet()
    {
        $this->_addHeader('Content-Type:application/json');
        return $this->_callFunction();
    }

    private function _handlePost()
    {
        $this->_addHeader('Content-type:application/json');
        return $this->_callFunction();
    }

    private function _handlePut()
    {
        $this->_addHeader('Content-type:application/json');
        return $this->_parameters;
    }

    private function _handleDelete()
    {
        $this->_addHeader('Content-type:application/json');
        return $this->_parameters;
    }

    private function _handleOptions()
    {
        $this->_addHeader('Allow: OPTIONS, GET, HEAD, POST, PUT, DELETE');
        return null;
    }

    private function _handleHead()
    {
        return null;
    }

    private function _callFunction()
    {
        //print_r($this->_request_body);
        $settings = isset($this->_request_body['settings']) ? $this->_request_body['settings'] : null;
        try {
            $ibe = \PressmindBooking\IBE\Factory::create($this->_request_body['bookingObject'], $settings);
        } catch (\Exception $e) {
            $this->_success = false;
            $code = ($e->getCode() == 0) ? 500 : $e->getCode();
            return ['code' => $code, 'message' => $e->getMessage(), 'original_message' => $e->getMessage()];
        }
        if(method_exists($ibe, $this->_action)) {
            try {
                return $ibe->{$this->_action}();
            } catch (Exception $e) {
                $this->_success = false;
                if($e->getCode() == 1) { //This is a wanted Exception used for user notification
                    return ['code' => '500', 'message' => $e->getMessage(), 'original_message' => $e->getMessage(), 'trace' => null];
                } else {
                    $trace = null;
                    if (defined('DEBUG') && DEBUG === true) {
                        $trace = $e->getTrace();
                    }
                    return ['code' => '500', 'message' => 'method ' . $this->_action . ' threw error: ' . $e->getMessage(), 'original_message' => $e->getMessage(), 'trace' => $trace];
                }
            }
        } else {
            $this->_success = false;
            return ['code' => '500', 'message' => 'function  ' . $this->_action . ' does not exist.', 'original_message' => 'function  ' . $this->_action . ' does not exist.'];
        }
    }

    private function _handleMethodError()
    {
        $this->_addHeader('Content-type:application/json');
        $this->_success = false;
        return ['code' => '500', 'message' => 'foo'];
    }
}
