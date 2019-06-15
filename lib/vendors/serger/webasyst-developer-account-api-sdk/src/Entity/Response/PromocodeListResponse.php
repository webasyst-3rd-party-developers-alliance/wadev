<?php
/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @copyright Serge Rodovnichenko, 2019
 * @license MIT
 */

namespace SergeR\WebasystDeveloperAccount\Entity\Response;

use SergeR\WebasystDeveloperAccount\Entity\PromocodeEntity;

/**
 * Class PromocodeListResponse
 * @package SergeR\WebasystDeveloperAccount\Entity\Response
 */
class PromocodeListResponse
{
    /** @var PromocodeEntity[] */
    protected $promocodes = [];

    public function __construct($data)
    {
        foreach ($data as $datum) {
            $this->addPromocode(PromocodeEntity::fromArray($datum));
        }
    }

    /**
     * @return PromocodeEntity[]
     */
    public function getPromocodes()
    {
        return $this->promocodes;
    }

    protected function addPromocode(PromocodeEntity $entity)
    {
        $this->promocodes[] = clone $entity;
        return $this;
    }

    /**
     * @param PromocodeEntity[] $promocodes
     * @return $this
     */
    protected function setPromocodes(PromocodeEntity ...$promocodes)
    {
        $this->promocodes = $promocodes;
        return $this;
    }


}