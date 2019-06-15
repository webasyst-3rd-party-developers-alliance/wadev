<?php
/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @copyright Serge Rodovnichenko, 2019
 * @license MIT
 */

namespace SergeR\WebasystDeveloperAccount\Entity\Response\Part\Order;

use DateTimeImmutable;
use SergeR\WebasystDeveloperAccount\Entity\AbstractResponseEntity;

/**
 * Class OrderTransactionItem
 * @package SergeR\WebasystDeveloperAccount\Entity\Response\Part\Order
 */
class OrderTransactionItem extends AbstractResponseEntity
{
    /** @var DateTimeImmutable */
    protected $datetime;

    /** @var float */
    protected $amount = 0.0;

    /** @var string */
    protected $currency = '';

    /** @var string */
    protected $comment = '';

    public function __construct(array $data)
    {
        $this->datetime = date_create_immutable();
        $this->_fromArray($data);
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * @param DateTimeImmutable|string $datetime
     * @return OrderTransactionItem
     */
    public function setDatetime($datetime)
    {
        if (is_string($datetime)) {
            $_datetime = date_create_immutable_from_format('Y-m-d H:i:s', $datetime);
            if ($_datetime) {
                return $this->setDatetime($_datetime);
            } else {
                $this->_unparsed_items['datetime'] = $datetime;
            }
        } elseif (!($datetime instanceof DateTimeImmutable)) {
            $this->_unparsed_items['datetime'] = $datetime;
        }
        $this->datetime = clone $datetime;
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
     * @return OrderTransactionItem
     */
    public function setAmount($amount)
    {
        $this->amount = (float)$amount;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     * @return OrderTransactionItem
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
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
     * @return OrderTransactionItem
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }
}
