<?php

class wadevTransactionModel extends wadevModel
{
    public function findLast($limit = 10)
    {
        return $this->where('contact_id='.wa()->getUser()->getId())->order('datetime DESC')->limit($limit)->fetchAll();
    }
}