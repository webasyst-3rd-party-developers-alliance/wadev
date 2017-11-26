<?php

/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @copyright Serge Rodovnichenko, 2017
 */
class wadevWebasystMyApi
{
    /** @var wadevNet */
    protected $net;

    protected $api_key;

    protected $api_url = 'https://www.webasyst.com/my/api/developer/';

    public function __construct($api_key)
    {
        if (empty($api_key)) {
            throw new waException('Key is required for API access');
        }
        $this->api_key = $api_key;
        $this->net = new wadevNet(['format' => waNet::FORMAT_JSON, 'expected_http_code' => null], ['X-API-Key' => $api_key]);
    }

    /**
     * @return array
     * @throws waException
     */
    public function balance()
    {
        $response = $this->query('balance/');

        return $response['data'];
    }

    /**
     * @param array $options
     * @return array
     * @throws waException
     */
    public function transactions(array $options = [])
    {
        $last = !empty($options['last']) ? min(max((int)$options['last'], 1), 100) : 10;
        $response = $this->query('ca/', ['last' => $last]);

        return $response['data'];
    }

    /**
     * @param string $domain
     * @param null|string $product
     * @return array
     * @throws waException
     */
    public function check($domain, $product = null)
    {
        $params = ['domain' => $domain];
        if (!empty($product)) {
            $params['product'] = $product;
        }
        $response = $this->query('check/', $params);

        return $response['data'];
    }

    /**
     * @param string $id
     * @return array
     * @throws waException
     */
    public function order($id)
    {
        $response = $this->query('order/', ['id' => $id]);

        return $response['data'];
    }

    /**
     * @param null|string $code
     * @return array
     * @throws waException
     */
    public function getPromocode($code = null)
    {
        $params = [];
        if (!is_null($code)) {
            $params['code'] = $code;
        }

        $response = $this->query('promocode/', $params);

        return $response['data'];
    }

    /**
     * Создание промо-кода.
     *
     * Важно! Если нужно ограничение по сроку действия, то должны быть указаны обе даты, начальная и конечная! Иначе
     * промо-код считается бессрочным!
     *
     * @param string $code промо-код
     * @param int $percent размер скидки в процентах
     * @param array $products список продуктов, к которым применяется скидка (множественный). Еабор строк типа
     *     'shop/plugin/slug'
     * @param array $options опциональные параметры:
     *                        - type: тип промо-кода (одноразовый или многоразовый) (опционально). Допустимые значения:
     *                          single, multi. По умолчанию: single
     *                        - start_date: дата начала действия промо-кода (опционально). Формат: YYYY-MM-DD
     *                        - end_date: дата окончания действия промо-кода (опционально). Формат: YYYY-MM-DD
     * @return array
     * @throws waException
     */
    public function createPromocode($code, $percent, array $products, array $options = [])
    {
        $params = array_merge($options, ['code' => $code, 'percent' => min(max((int)$percent, 1), 100), 'products' => $products]);
        $response = $this->query('promocode/', $params, waNet::METHOD_POST);

        return $response['data'];
    }

    /**
     * Удаление промо-кода
     *
     * @param string $code
     * @return array
     * @throws waException
     */
    public function deletePromocode($code)
    {
        $response = $this->query('promocode/', ['code' => $code], waNet::METHOD_DELETE);

        return $response['data'];
    }

    protected function _validateResponse($response)
    {
        if (!is_array($response) || !isset($response['status'])) {
            throw new waException();
        }

        if ($response['status'] == 'fail') {
            if (isset($response['errors'])) {
                if (is_array($response['errors']) && is_string($response['errors'][0])) {
                    throw new waException($response['errors'][0]);
                }
            }
            throw new waException('Request error');
        }

        if ($this->net->getResponseHeader('http_code') != '200') {
            throw new waException('Error ' . $this->net->getResponseHeader('http_code'), $this->net->getResponseHeader('http_code'));
        }

        return true;
    }

    private function query($url, $params = [], $method = waNet::METHOD_GET)
    {
        $response = $this->net->reset()->query($this->api_url . $url, $params, $method);
        $this->_validateResponse($response);

        if (!isset($response['data'])) {
            $response['data'] = '';
        }

        return $response;
    }
}
