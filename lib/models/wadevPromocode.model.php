<?php

class wadevPromocodeModel extends wadevBasePromocodeModel
{
    /**
     * @param int $limit
     *
     * @return wadevPromocodeModel|wadevPromocodeModel[]|null
     */
    public function findLast($limit = 10)
    {
        return self::generateModels($this->order('create_datetime DESC')->limit($limit)->fetchAll(),
            $limit === 1 ?: false);
    }

    public function beforeSave()
    {
        if ($this->isNewRecord) {
            $this->hash = md5($this->create_datetime . $this->code . $this->type);
        }
        return true;
    }
}