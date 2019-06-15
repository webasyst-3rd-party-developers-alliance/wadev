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
 * Class BalanceResponse
 * @package SergeR\WebasystDeveloperAccount\Entity\Response
 */
class BalanceResponse extends AbstractResponse
{
    /** @var float */
    protected $balance = 0.0;

    /** @var string */
    protected $currency = 'RUB';

    /** @var DateTimeImmutable */
    protected $update_datetime;

    public function __construct(array $data = [])
    {
        $this->update_datetime = date_create_immutable();
        $this->_fromArray($data);
    }

    /**
     * @return float
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @param float $balance
     */
    protected function setBalance($balance)
    {
        $this->balance = (float)str_replace(',', '.', $balance);
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
     */
    protected function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getUpdateDatetime()
    {
        return $this->update_datetime;
    }

    /**
     * @param DateTimeImmutable $update_datetime
     */
    protected function setUpdateDatetime($update_datetime)
    {
        $date = date_create_immutable_from_format('Y-m-d H:i:s', $update_datetime);

        if ($date) {
            $this->update_datetime = $date;
        } else {
            $this->_unparsed_items['update_datetime'] = $update_datetime;
        }
    }
}