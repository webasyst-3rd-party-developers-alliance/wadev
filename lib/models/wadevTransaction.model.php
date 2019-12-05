<?php

/**
 * Class wadevTransactionModel
 */
class wadevTransactionModel extends wadevModel
{
    protected $table = 'wadev_transaction';

    /**
     * @param int $contact_id
     * @param int $limit
     * @return array
     */
    public function findLast($contact_id, $limit = 10)
    {
        return (array)$this->select('*')->where('contact_id=i:contact_id', ['contact_id' => $contact_id])
            ->order('datetime DESC')
            ->limit($limit)
            ->fetchAll();
    }
}
