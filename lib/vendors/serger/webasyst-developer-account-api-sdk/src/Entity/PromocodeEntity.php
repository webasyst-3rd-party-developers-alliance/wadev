<?php
/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @copyright Serge Rodovnichenko, 2019
 * @license MIT
 */

namespace SergeR\WebasystDeveloperAccount\Entity;

use DateTimeImmutable;

/**
 * Class PromocodeItem
 * @package SergeR\WebasystDeveloperAccount\Entity
 */
class PromocodeEntity extends AbstractResponseEntity
{
    /** @var DateTimeImmutable */
    protected $create_datetime;

    /** @var string */
    protected $code = '';

    /** @var float */
    protected $percent = 0;

    /** @var string */
    protected $type = '';

    /** @var DateTimeImmutable|null */
    protected $start_date = null;

    /** @var DateTimeImmutable|null */
    protected $end_date = null;

    /** @var int */
    protected $usage = 0;

    /** @var string[] */
    protected $products = [];

    /**
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data)
    {
        $self = new self();
        $self->_fromArray($data);
        return $self;
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
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $create_datetime)) {
                $_create_datetime = date_create_immutable_from_format('Y-m-d', $create_datetime);
            } elseif (preg_match('/^\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}$/', $create_datetime)) {
                $_create_datetime = date_create_immutable_from_format('Y-m-d H:i:s', $create_datetime);
            } else {
                $_create_datetime = false;
            }

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
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return float
     */
    public function getPercent()
    {
        return $this->percent;
    }

    /**
     * @param int $percent
     * @return $this
     */
    public function setPercent($percent)
    {
        $this->percent = (float)$percent;
        return $this;
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
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getStartDate()
    {
        return $this->start_date;
    }

    /**
     * @param DateTimeImmutable|null $start_date
     * @return $this
     */
    public function setStartDate($start_date)
    {
        if ($start_date === null) {
            $this->start_date = null;
        } elseif (is_string($start_date)) {
            $_date = date_create_immutable_from_format('Y-m-d', $start_date);
            if ($_date) {
                return $this->setStartDate($_date);
            } else {
                $this->_unparsed_items['create_datetime'] = $start_date;
            }
        } elseif ($start_date instanceof DateTimeImmutable) {
            $this->start_date = clone $start_date;
        } else {
            $this->_unparsed_items['start_date'] = $start_date;
        }

        return $this;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getEndDate()
    {
        return $this->end_date;
    }

    /**
     * @param DateTimeImmutable|null $end_date
     * @return $this
     */
    public function setEndDate($end_date)
    {
        if ($end_date === null) {
            $this->start_date = null;
        } elseif (is_string($end_date)) {
            $_date = date_create_immutable_from_format('Y-m-d', $end_date);
            if ($_date) {
                return $this->setEndDate($_date);
            } else {
                $this->_unparsed_items['create_datetime'] = $end_date;
            }
        } elseif ($end_date instanceof DateTimeImmutable) {
            $this->end_date = clone $end_date;
        } else {
            $this->_unparsed_items['end_date'] = $end_date;
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getUsage()
    {
        return $this->usage;
    }

    /**
     * @param int $usage
     * @return $this
     */
    public function setUsage($usage)
    {
        $this->usage = (int)$usage;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param $product
     * @return $this
     */
    public function addProduct($product)
    {
        $this->products[] = $product;
        return $this;
    }

    /**
     * @param string[] $products
     * @return $this
     */
    public function setProducts(array $products)
    {
        $this->products = $products;
        return $this;
    }
}