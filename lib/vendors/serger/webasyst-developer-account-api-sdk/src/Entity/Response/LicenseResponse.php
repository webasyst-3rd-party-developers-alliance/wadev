<?php
/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @copyright Serge Rodovnichenko, 2019
 * @license MIT
 */

namespace SergeR\WebasystDeveloperAccount\Entity\Response;

use DateTimeImmutable;
use SergeR\WebasystDeveloperAccount\Entity\AbstractResponse;

/**
 * Class LicenseResponse
 * @package SergeR\WebasystDeveloperAccount\Entity\Response
 */
class LicenseResponse extends AbstractResponse
{
    protected $product = '';

    /** @var DateTimeImmutable */
    protected $create_datetime;

    /** @var null|DateTimeImmutable */
    protected $inst_datetime = null;

    /** @var int */
    protected $order_id = 0;

    /** @var int */
    protected $leasing = 0;

    public function __construct(array $data)
    {
        $this->create_datetime = date_create_immutable();
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
     * @return DateTimeImmutable
     */
    public function getCreateDatetime()
    {
        return $this->create_datetime;
    }

    /**
     * @param DateTimeImmutable|string $create_datetime
     * @return $this
     */
    public function setCreateDatetime($create_datetime)
    {
        if (is_string($create_datetime)) {
            $_create_datetime = date_create_immutable_from_format('Y-m-d H:i:s', $create_datetime);
            if ($_create_datetime) {
                return $this->setCreateDatetime($_create_datetime);
            } else {
                $this->_unparsed_items['create_datetime'] = $create_datetime;
            }
        } elseif ($create_datetime instanceof DateTimeImmutable) {
            $this->create_datetime = clone $create_datetime;
        } else {
            $this->_unparsed_items['create_datetime'] = $create_datetime;
        }

        return $this;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getInstDatetime()
    {
        return $this->inst_datetime;
    }

    /**
     * @param string|DateTimeImmutable|null $inst_datetime
     * @return $this
     */
    public function setInstDatetime($inst_datetime)
    {
        if ($inst_datetime === null) {
            $this->inst_datetime = $inst_datetime;
        } elseif (is_string($inst_datetime)) {
            $_inst_datetime = date_create_immutable_from_format('Y-m-d H:i:s', $inst_datetime);
            if ($_inst_datetime) {
                return $this->setInstDatetime($_inst_datetime);
            } else {
                $this->_unparsed_items['inst_datetime'] = $inst_datetime;
            }
        } elseif ($inst_datetime instanceof DateTimeImmutable) {
            $this->inst_datetime = clone $inst_datetime;
        } else {
            $this->_unparsed_items['inst_datetime'] = $inst_datetime;
        }
        return $this;
    }

    /**
     * @return int
     */
    public function getOrderId()
    {
        return $this->order_id;
    }

    /**
     * @param int $order_id
     * @return $this
     */
    public function setOrderId($order_id)
    {
        $this->order_id = (int)$order_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getLeasing()
    {
        return $this->leasing;
    }

    /**
     * @param int $leasing
     * @return $this
     */
    public function setLeasing($leasing)
    {
        $this->leasing = (int)$leasing;
        return $this;
    }

    /**
     * @return bool
     */
    public function isLeased()
    {
        return (bool)$this->getLeasing();
    }
}