<?php

class wadevTransactionModel extends wadevBaseTransactionModel
{
    /**
     * @param int $limit
     *
     * @return null|wadevTransactionModel[]
     */
    public function getLast($limit = 10)
    {
        return self::generateModels($this->order('id DESC')->limit($limit)->fetchAll());
    }

}