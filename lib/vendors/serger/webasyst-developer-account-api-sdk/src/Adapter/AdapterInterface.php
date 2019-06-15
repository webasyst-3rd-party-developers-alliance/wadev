<?php
/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @copyright Serge Rodovnichenko, 2019
 * @license MIT
 */

namespace SergeR\WebasystDeveloperAccount\Adapter;

use Exception;
use RuntimeException;
use SergeR\WebasystDeveloperAccount\Exception\ResponseException;

/**
 * Interface AdapterInterface
 * @package SergeR\WebasystDeveloperAccount\Adapter
 */
interface AdapterInterface
{
    /**
     * Performs a HTTP GET request
     *
     * @param string $url
     * @param array $headers
     * @param array $query_params
     * @throws Exception
     * @throws ResponseException
     * @throws RuntimeException
     */
    public function get($url, array $headers = [], array $query_params = []);

    /**
     * Performs a HTTP POST request
     *
     * @param string $url
     * @param array $headers
     * @param mixed $content
     */
    public function post($url, array $headers = [], $content = '');

    /**
     * Performs a HTTP DELETE request
     *
     * @param string $url
     * @param array $headers
     * @param array $query_params
     * @throws ResponseException
     * @throws RuntimeException
     * @throws Exception
     */
    public function delete($url, array $headers = [], array $query_params = []);
}