<?php
/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @copyright Serge Rodovnichenko, 2019
 * @license MIT
 */

namespace SergeR\WebasystDeveloperAccount\Api;

use Exception;
use RuntimeException;
use SergeR\WebasystDeveloperAccount\Entity\Response\BalanceResponse;
use SergeR\WebasystDeveloperAccount\Exception\ResponseException;

/**
 * Class Balance
 * @package SergeR\WebasystDeveloperAccount\Api
 */
class Balance extends AbstractApi
{
    /**
     * @throws Exception|RuntimeException|ResponseException
     */
    public function get()
    {
        $response = $this->adapter->get('balance/');
        $response = json_decode($response, true);

        return new BalanceResponse($response);
    }
}