<?php


namespace Pressmind\Router;


class Route
{
    public $type;
    public $method;
    public $route;
    private $_params;
    private $_id;

    public function __construct($type = null, $method = null, $route = null)
    {
        $this->route = is_null($route) ? null: $route;
        $this->type = is_null($type) ? null: $type;
        $this->method = is_null($method) ? null: $method;
        $this->parse($route);
    }

    private function parse($route) {
         // Convert the route to a regular expression: escape forward slashes
        $route = preg_replace('/\//', '\\/', $route);
        // Convert variables e.g. {controller}
        $route = preg_replace('/\{([a-z0-9\-]+)\}/', '(?P<\1>[a-z-0-9\-]+)', $route);
        // Convert variables with custom regular expressions e.g. {id:\d+}
        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);
        // Add start and end delimiters, and case insensitive flag
        $route = '/^' . $route . '$/i';
        $this->route = $route;
        $this->_id = md5($route);
    }

    public function match($request_uri, $request_method) {
        $params = [];
        if(strpos($request_uri, '?') !== false) {
            $request_array = explode('?', $request_uri);
            $request_uri = $request_array[0];
            parse_str($request_array[1], $params);
        }
        $request_uri = rtrim($request_uri, '/');
        if(empty($request_uri)) $request_uri = '/';
        if ($request_method == $this->method && preg_match($this->route, $request_uri, $matches)) {
            foreach ($matches as $key => $match) {
                if (is_string($key)) {
                    $params[$key] = $match;
                }
            }
            return $params;
        } else if($request_uri == '/') {
            return ['controller' => 'index', 'action' => 'index'];
        } else {
            return false;
        }
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->_params;
    }
}
