<?php
/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @copyright Serge Rodovnichenko, 2019
 * @license MIT
 */

namespace SergeR\WebasystDeveloperAccount\Api;

use SergeR\WebasystDeveloperAccount\Adapter\AdapterInterface;

/**
 * Class AbstractApi
 * @package SergeR\WebasystDeveloperAccount\Api
 */
abstract class AbstractApi
{
    /** @var AdapterInterface */
    protected $adapter;

    /**
     * AbstractApi constructor.
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }
}