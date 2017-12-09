<?php

/**
 * Class wadevProduct
 *
 * @property wadevProductModel $model
 */
class wadevProduct extends wadevEntity
{
    public function __construct($model = null)
    {
        if ($model === null) {
            $model = new wadevProductModel;
        }
        parent::__construct($model);
    }

    /**
     * Получает продукты из API
     *
     * @return bool|int
     */
    public function updateFromApi()
    {
        return 0;
    }
}