<?php


namespace Pressmind\MVC;


class Response
{
    /**
     * @var array
     */
    private $_headers = [];

    /**
     * @var int
     */
    private $_code = 200;

    /**
     * @var string
     */
    private $_content_type;

    /**
     * @var string
     */
    private $_content_encoding;

    /**
     * @var string
     */
    private $_body;

    /**
     * @var array
     */
    private $_error_messages = [
        404 => 'No corresponding route found',
        500 => 'An internal Error occured'
    ];

    public function __construct()
    {

    }

    public function setBody($pBodyContent)
    {
        $this->_body = $pBodyContent;
    }

    public function setContentEncoding($pContentEncoding)
    {
        $this->_content_encoding = $pContentEncoding;
    }

    public function setContentType($pContentType)
    {
        $this->_content_type = $pContentType;
    }

    /**
     * @param int $pCode
     */
    public function setCode($pCode)
    {
        $this->_code = $pCode;
    }

    /**
     * @param string $pKey
     * @param string $pValue
     */
    public function addHeader($pKey, $pValue)
    {
        $this->_headers[$pKey] = $pValue;
    }

    public function send()
    {
        $this->addHeader('Content-Type', $this->_content_type);
        http_response_code($this->_code);
        foreach ($this->_headers as $header_key => $header_value) {
            header($header_key . ': ' . $header_value);
        }
        echo $this->_body;
    }
}
