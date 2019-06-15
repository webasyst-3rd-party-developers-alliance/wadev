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
 * Class OrderLicenseItem
 * @package SergeR\WebasystDeveloperAccount\Entity\Response\Part\Order
 */
class OrderLicenseItem extends AbstractResponseEntity
{
    /** @var string */
    protected $product = '';

    /** @var string|null */
    protected $domain;

    /** @var DateTimeImmutable|null */
    protected $inst_datetime;

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
     * @return string|null
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param string|null $domain
     * @return $this
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
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
     * @param DateTimeImmutable|null $inst_datetime
     * @return $this
     */
    public function setInstDatetime($inst_datetime)
    {
        if (is_string($inst_datetime)) {
            $_datetime = date_create_immutable_from_format('Y-m-d H:i:s', $inst_datetime);
            if ($_datetime) {
                return $this->setInstDatetime($_datetime);
            } else {
                $this->_unparsed_items['inst_datetime'] = $inst_datetime;
            }
        } elseif (!($this->inst_datetime instanceof DateTimeImmutable)) {
            $this->_unparsed_items['inst_datetime'] = $inst_datetime;
        }

        $this->inst_datetime = $inst_datetime;
        return $this;
    }
}