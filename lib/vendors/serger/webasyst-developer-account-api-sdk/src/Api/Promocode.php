<?php
/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @copyright Serge Rodovnichenko, 2019
 * @license MIT
 */

namespace SergeR\WebasystDeveloperAccount\Api;

use Exception;
use InvalidArgumentException;
use RuntimeException;
use SergeR\WebasystDeveloperAccount\Entity\PromocodeEntity;
use SergeR\WebasystDeveloperAccount\Entity\Response\PromocodeListResponse;
use SergeR\WebasystDeveloperAccount\Entity\Response\PromocodeResponse;
use SergeR\WebasystDeveloperAccount\Exception\ResponseException;

/**
 * Class Promocode
 * @package SergeR\WebasystDeveloperAccount\Api
 */
class Promocode extends AbstractApi
{
    /**
     * @return PromocodeListResponse
     * @throws ResponseException
     * @throws RuntimeException
     * @throws Exception
     */
    public function getList()
    {
        $response = $this->adapter->get('promocode/');
        $response = json_decode($response, true);

        return new PromocodeListResponse($response);
    }

    /**
     * @param string $code
     * @return PromocodeResponse
     * @throws ResponseException
     * @throws RuntimeException
     * @throws Exception
     */
    public function getCode($code)
    {
        if (!$code) {
            throw new InvalidArgumentException('Code required');
        }

        $response = $this->adapter->get('promocode/', [], ['code' => $code]);
        $response = json_decode($response, true);

        return new PromocodeResponse($response);
    }

    /**
     * @param PromocodeEntity $promocode
     * @return bool
     * @throws RuntimeException
     * @throws Exception
     * @throws ResponseException
     */
    public function create(PromocodeEntity $promocode)
    {
        $params = [
            'code'     => $promocode->getCode(),
            'percent'  => str_replace(',', '.', $promocode->getPercent()),
            'products' => $promocode->getProducts()
        ];

        if ($promocode->getType()) {
            $params['type'] = $promocode->getType();
        }

        if ($promocode->getStartDate()) {
            $params['start_date'] = $promocode->getStartDate()->format('Y-m-d');
        }

        if ($promocode->getEndDate()) {
            $promocode['end_date'] = $promocode->getEndDate()->format('Y-m-d');
        }

        $this->adapter->post('promocode/', ['Content-Type' => 'application/x-www-form-urlencoded'], $params);

        return true;
    }

    /**
     * @param string $code
     * @return bool
     * @throws ResponseException
     * @throws RuntimeException
     * @throws Exception
     */
    public function delete($code)
    {
        if (!$code) {
            throw new InvalidArgumentException('Code required');
        }

        $this->adapter->delete('promocode/', [], ['code' => $code]);

        return true;
    }
}