<?php
/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @copyright Serge Rodovnichenko, 2019
 * @license MIT
 */

namespace SergeR\WebasystDeveloperAccount\Entity\Response\Part\Order;

use SergeR\WebasystDeveloperAccount\Entity\AbstractResponseEntity;

/**
 * Class OrderDiscountItem
 * @package SergeR\WebasystDeveloperAccount\Entity\Response\Part\Order
 */
class OrderDiscountItem extends AbstractResponseEntity
{
    /** @var string */
    protected $type = '';

    /** @var int */
    protected $percent = 0;

    /** @var string|null */
    protected $code;

    public function __construct(array $data)
    {
        $this->_fromArray($data);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return OrderDiscountItem
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return int
     */
    public function getPercent()
    {
        return $this->percent;
    }

    /**
     * @param int $percent
     * @return OrderDiscountItem
     */
    public function setPercent($percent)
    {
        $this->percent = (int)$percent;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string|null $code
     * @return OrderDiscountItem
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }
}