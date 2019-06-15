<?php
/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @copyright Serge Rodovnichenko, 2019
 * @license MIT
 */

namespace SergeR\WebasystDeveloperAccount;

use SergeR\WebasystDeveloperAccount\Adapter\AdapterInterface;
use SergeR\WebasystDeveloperAccount\Api\Balance;
use SergeR\WebasystDeveloperAccount\Api\License;
use SergeR\WebasystDeveloperAccount\Api\Order;
use SergeR\WebasystDeveloperAccount\Api\Promocode;
use SergeR\WebasystDeveloperAccount\Api\Transaction;

/**
 * Class WebasystDeveloperAccount
 * @package SergeR\WebasystDeveloperAccount
 */
class WebasystDeveloperAccount
{
    /** @var AdapterInterface */
    protected $adapter;

    /**
     * WebasystDeveloperAccount constructor.
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = clone $adapter;
    }

    /**
     * @return Balance
     */
    public function balance()
    {
        return new Balance(clone $this->adapter);
    }

    public function transaction()
    {
        return new Transaction(clone $this->adapter);
    }

    public function license()
    {
        return new License(clone $this->adapter);
    }

    public function order()
    {
        return new Order(clone $this->adapter);
    }

    public function promocode()
    {
        return new Promocode(clone $this->adapter);
    }
}