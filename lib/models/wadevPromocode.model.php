<?php

class wadevPromocodeModel extends wadevBasePromocodeModel
{
    const TYPE_SINGLE = 'single';
    const TYPE_MULTI = 'multi';

    /**
     * @param int $limit
     *
     * @return wadevPromocodeModel|wadevPromocodeModel[]|null
     */
    public function findLast($limit = 10)
    {
        return self::generateModels($this->where('contact_id='.wa()->getUser()->getId())->order('create_datetime DESC')->limit($limit)->fetchAll(),
            $limit === 1 ?: false);
    }

    public function beforeSave()
    {
        if ($this->isNewRecord) {
            $this->hash = md5($this->create_datetime . $this->code . $this->type);
        }
        return true;
    }

    public function getTypeName()
    {
        switch ($this->getAttribute('type')) { // todo: надо что-то делать с переопределением наследства от waModel
            case self::TYPE_MULTI:
                return 'M';
            case self::TYPE_SINGLE:
                return 'S';
        }
        return '';
    }

    public function findAll()
    {
        $promos = $this->getByField('contact_id', wa()->getUser()->getId(), true);
        return self::generateModels($promos);
    }
}