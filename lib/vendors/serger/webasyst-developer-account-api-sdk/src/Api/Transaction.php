<?php
/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @copyright Serge Rodovnichenko, 2019
 * @license MIT
 */

namespace SergeR\WebasystDeveloperAccount\Api;

use Exception;
use RuntimeException;
use SergeR\WebasystDeveloperAccount\Entity\Response\TransactionListResponse;
use SergeR\WebasystDeveloperAccount\Exception\ResponseException;

/**
 * Class Transaction
 * @package SergeR\WebasystDeveloperAccount\Api
 */
class Transaction extends AbstractApi
{
    /**
     * @param int $last
     * @return TransactionListResponse
     * @throws ResponseException
     * @throws RuntimeException
     * @throws Exception
     */
    public function getList($last = 10)
    {
        $params = array_filter(['last' => $last]);
        $response = $this->adapter->get('ca/', [], $params);
        $response = json_decode($response, true);

        return new TransactionListResponse($response);
    }
}