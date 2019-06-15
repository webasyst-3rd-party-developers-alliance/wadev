<?php
/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @copyright Serge Rodovnichenko, 2019
 * @license MIT
 */

namespace SergeR\WebasystDeveloperAccount\Entity\Response;

use SergeR\WebasystDeveloperAccount\Entity\Response\Part\Transaction\TransactionItem;

/**
 * Class TransactionListResponse
 * @package SergeR\WebasystDeveloperAccount\Entity\Response
 */
class TransactionListResponse
{
    /** @var TransactionItem[] */
    protected $transactions = [];

    public function __construct(array $data)
    {
        foreach ($data as $datum) {
            $this->addTransaction(new TransactionItem($datum));
        }
    }

    public function addTransaction(TransactionItem $item)
    {
        $this->transactions[] = $item;
    }

    /**
     * @param TransactionItem[] $items
     */
    public function setTransactions(TransactionItem ...$items)
    {
        $this->transactions = $items;
    }

    /**
     * @return TransactionItem[]
     */
    public function getResults()
    {
        return $this->transactions;
    }
}