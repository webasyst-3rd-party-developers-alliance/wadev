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
use SergeR\WebasystDeveloperAccount\Entity\Response\LicenseListResponse;
use SergeR\WebasystDeveloperAccount\Entity\Response\LicenseResponse;
use SergeR\WebasystDeveloperAccount\Exception\ResponseException;

/**
 * Class License
 * @package SergeR\WebasystDeveloperAccount\Api
 */
class License extends AbstractApi
{
    /**
     * @param string $product
     * @param string $domain
     * @return LicenseResponse
     * @throws ResponseException
     * @throws RuntimeException
     * @throws Exception
     */
    public function getByProductAndDomain($product, $domain)
    {
        $product = trim($product);
        $domain = trim($domain);

        if (!$product || !$domain) {
            throw new InvalidArgumentException('Required both domain name and product id');
        }

        $response = $this->adapter->get('check/', [], ['domain' => $domain, 'product' => $product]);
        $response = json_decode($response, true);

        return new LicenseResponse($response);
    }

    /**
     * @param string $domain
     * @return LicenseListResponse
     * @throws ResponseException
     * @throws RuntimeException
     * @throws Exception
     */
    public function listByDomain($domain)
    {
        $domain = trim($domain);

        if (!$domain) {
            throw new InvalidArgumentException('Domain name required');
        }

        $response = $this->adapter->get('check/', [], ['domain' => $domain]);
        $response = json_decode($response, true);

        return new LicenseListResponse($response);
    }
}