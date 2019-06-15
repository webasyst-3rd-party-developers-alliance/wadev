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
use SergeR\WebasystDeveloperAccount\Entity\Response\OrderResponse;
use SergeR\WebasystDeveloperAccount\Exception\ResponseException;

/**
 * Class Order
 * @package SergeR\WebasystDeveloperAccount\Api
 */
class Order extends AbstractApi
{
    /**
     * @param $order_id
     * @return OrderResponse
     * @throws ResponseException
     * @throws RuntimeException
     * @throws Exception
     */
    public function get($order_id)
    {
        if (!$order_id) {
            throw new InvalidArgumentException('Order ID must be a positive integer');
        }

        $response = $this->adapter->get('order/', [], ['id' => $order_id]);
        $response = json_decode($response, true);

        return new OrderResponse($response);
    }
}