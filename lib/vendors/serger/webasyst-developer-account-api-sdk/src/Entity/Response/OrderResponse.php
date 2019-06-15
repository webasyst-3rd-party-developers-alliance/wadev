<?php
/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @copyright Serge Rodovnichenko, 2019
 * @license MIT
 */

namespace SergeR\WebasystDeveloperAccount\Entity\Response;

use SergeR\WebasystDeveloperAccount\Entity\AbstractResponse;
use SergeR\WebasystDeveloperAccount\Entity\Response\Part\Order\OrderDiscountItem;
use SergeR\WebasystDeveloperAccount\Entity\Response\Part\Order\OrderLicenseItem;
use SergeR\WebasystDeveloperAccount\Entity\Response\Part\Order\OrderTransactionItem;

/**
 * Class OrderResponse
 * @package SergeR\WebasystDeveloperAccount\Entity\Response
 */
class OrderResponse extends AbstractResponse
{
    protected $status = '';

    /** @var OrderDiscountItem[] */
    protected $discounts = [];

    /** @var OrderTransactionItem[] */
    protected $transactions = [];

    /** @var OrderLicenseItem[] */
    protected $licenses = [];

    public function __construct(array $data)
    {
        if (isset($data['status'])) {
            $this->setStatus((string)$data['status']);
        }

        if (isset($data['discounts']) && is_array($data['discounts'])) {
            foreach ($data['discounts'] as $discount) {
                if (is_array($discount)) {
                    $this->addDiscount( new OrderDiscountItem($discount));
                } else {
                    $this->_unparsed_items['discounts'] = $data['discounts'];
                }
            }
        }

        if (isset($data['transactions']) && is_array($data['transactions'])) {
            foreach ($data['transactions'] as $transaction) {
                if (is_array($transaction)) {
                    $this->addTransaction(new OrderTransactionItem($transaction));
                } else {
                    $this->_unparsed_items['transactions'] = $data['transactions'];
                }
            }
        }

        if (isset($data['licenses']) && is_array($data['licenses'])) {
            foreach ($data['licenses'] as $license) {
                if (is_array($license)) {
                    $this->addLicense(new OrderLicenseItem($license));
                } else {
                    $this->_unparsed_items['licenses'] = $data['licenses'];
                }
            }
        }
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return $this
     */
    protected function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return OrderDiscountItem[]
     */
    public function getDiscounts()
    {
        return $this->discounts;
    }

    /**
     * @param OrderDiscountItem $item
     * @return $this
     */
    protected function addDiscount(OrderDiscountItem $item)
    {
        $this->discounts[] = clone $item;
        return $this;
    }

    /**
     * @param OrderDiscountItem[] $discounts
     * @return $this
     */
    protected function setDiscounts(OrderDiscountItem ...$discounts)
    {
        $this->discounts = $discounts;
        return $this;
    }

    /**
     * @return OrderTransactionItem[]
     */
    public function getTransactions()
    {
        return $this->transactions;
    }

    /**
     * @param OrderTransactionItem $item
     * @return $this
     */
    protected function addTransaction(OrderTransactionItem $item)
    {
        $this->transactions[] = $item;
        return $this;
    }

    /**
     * @param OrderTransactionItem[] $transactions
     * @return $this
     */
    protected function setTransactions(OrderTransactionItem ...$transactions)
    {
        $this->transactions = $transactions;
        return $this;
    }

    /**
     * @return OrderLicenseItem[]
     */
    public function getLicenses()
    {
        return $this->licenses;
    }

    /**
     * @param OrderLicenseItem $item
     * @return $this
     */
    protected function addLicense(OrderLicenseItem $item)
    {
        $this->licenses[] = $item;
        return $this;
    }

    /**
     * @param OrderLicenseItem[] $licenses
     * @return $this
     */
    protected function setLicenses(OrderLicenseItem ...$licenses)
    {
        $this->licenses = $licenses;
        return $this;
    }
}