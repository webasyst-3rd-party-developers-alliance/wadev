<?php
/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @copyright Serge Rodovnichenko, 2019
 * @license MIT
 */

namespace SergeR\WebasystDeveloperAccount\Entity\Response;

/**
 * Class LicenseListResponse
 * @package SergeR\WebasystDeveloperAccount\Entity\Response
 */
class LicenseListResponse
{
    /** @var LicenseResponse[] */
    protected $licenses = [];

    public function __construct(array $data)
    {
        foreach ($data as $datum) {
            $this->addTransaction(new LicenseResponse($datum));
        }
    }

    protected function addTransaction(LicenseResponse $item)
    {
        $this->licenses[] = $item;
    }

    /**
     * @param LicenseResponse[] $items
     */
    protected function setTransactions(LicenseResponse ...$items)
    {
        $this->licenses = $items;
    }

    /**
     * @return LicenseResponse[]
     */
    public function getResults()
    {
        return $this->licenses;
    }

}