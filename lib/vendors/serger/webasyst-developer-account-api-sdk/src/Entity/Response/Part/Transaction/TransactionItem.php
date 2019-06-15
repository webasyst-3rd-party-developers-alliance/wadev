<?php
/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @copyright Serge Rodovnichenko, 2019
 * @license MIT
 */

namespace SergeR\WebasystDeveloperAccount\Entity\Response\Part\Transaction;

use SergeR\WebasystDeveloperAccount\Entity\AbstractResponseEntity;

/**
 * Class TransactionItem
 * @package SergeR\WebasystDeveloperAccount\Entity\Response\Part\Transaction
 */
class TransactionItem extends AbstractResponseEntity
{
    /** @var string */
    protected $product = '';

    /** @var float */
    protected $balance_before = 0.0;

    /** @var float */
    protected $amount = 0.0;

    /** @var float */
    protected $balance_after = 0.0;

    /** @var string */
    protected $order_id = '';

    /** @var string */
    protected $comment = '';

    public function __construct(array $data)
    {
        $this->_fromArray($data);
    }

    /**
     * @return string
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param string $product
     * @return $this
     */
    public function setProduct($product)
    {
        $this->product = $product;
        return $this;
    }

    /**
     * @return float
     */
    public function getBalanceBefore()
    {
        return $this->balance_before;
    }

    /**
     * @param float $balance_before
     * @return $this
     */
    public function setBalanceBefore($balance_before)
    {
        $this->balance_before = (float)str_replace(',', '.', $balance_before);
        return $this;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->amount = (float)str_replace(',', '.', $amount);
        return $this;
    }

    /**
     * @return float
     */
    public function getBalanceAfter()
    {
        return $this->balance_after;
    }

    /**
     * @param float $balance_after
     * @return $this
     */
    public function setBalanceAfter($balance_after)
    {
        $this->balance_after = (float)str_replace(',', '.', $balance_after);
        return $this;
    }

    /**
     * @return string
     */
    public function getOrderId()
    {
        return $this->order_id;
    }

    /**
     * @param string $order_id
     * @return $this
     */
    public function setOrderId($order_id)
    {
        $this->order_id = $order_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     * @return $this
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }
}