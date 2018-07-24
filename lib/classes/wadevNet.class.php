<?php

/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @copyright Serge Rodovnichenko, 2017
 */
class wadevNet extends waNet
{
    protected $default_request_headers = array();
    protected $default_options = array();

    public function __construct($options = array(), $custom_headers = array())
    {
        parent::__construct($options, $custom_headers);
        $this->default_options = $this->options;
        $this->default_request_headers = $this->request_headers;
        $this->_reset();
    }

    /**
     * @param string $name
     * @return mixed
     */
    function __get($name)
    {
        if (!in_array($name, array('options', 'request_headers'))) {
            $trace = debug_backtrace();
            trigger_error(
                'Undefined property via __get(): ' . $name .
                ' in ' . $trace[0]['file'] .
                ' on line ' . $trace[0]['line'],
                E_USER_NOTICE);
            return null;
        }
        return $this->$name;
    }

    /**
     * Performs DELETE request
     *
     * @param $url
     * @return array|SimpleXMLElement|string
     * @throws waException
     */
    public function delete($url)
    {
        return $this->query($url, [], waNet::METHOD_DELETE);
    }

    /**
     * @param $url
     * @return array|SimpleXMLElement|string|waNet
     * @throws waException
     */
    public function get($url)
    {
        return $this->query($url, [], waNet::METHOD_GET);
    }

    /**
     * @param $url
     * @param array $data
     * @return array|SimpleXMLElement|string|waNet
     * @throws waException
     */
    public function post($url, $data = [])
    {
        return $this->query($url, $data, waNet::METHOD_POST);
    }

    public function query($url, $content = array(), $method = self::METHOD_GET, $callback = null)
    {
        $expected_http_code = $this->options['expected_http_code'];
        $this->options['expected_http_code'] = null;

        $response = parent::query($url, $content, $method);

        $this->options['expected_http_code'] = $expected_http_code;

        if ($this->options['expected_http_code'] !== null) {
            if (empty($this->response_header['http_code']) || ($this->response_header['http_code'] != $this->options['expected_http_code'])) {
                throw new waException(is_string($response) ? $response : 'Error', $this->response_header['http_code']);
            }
        }

        return $response;
    }

    /**
     * @param array $options
     * @return wadevNet $this
     */
    public function options(array $options)
    {
        $this->options = array_merge($this->options, $options);
        return $this;
    }

    /**
     * @param array $headers
     * @return wadevNet $this
     */
    public function requestHeaders(array $headers)
    {
        $this->request_headers = array_merge($this->request_headers, $headers);
        return $this;
    }

    /**
     * Chainable method to reset all setting, options, cookies, headers to their default values
     *
     * @return wadevNet $this
     */
    public function reset()
    {
        $this->_reset();
        return $this;
    }

    protected function _reset()
    {
        $this->accept_cookies = false;
        $this->cookies = null;
        $this->request_headers = $this->default_request_headers;
        $this->options = $this->default_options;
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $this->options['verify'] = false;
        }
    }
}
