<?php

class wadevPromocodeProductsModel extends wadevBasePromocodeProductsModel
{
    public function beforeSave()
    {
        if ($this->isNewRecord) {
            $this->usage = 0;
        }
        return true;
    }
}