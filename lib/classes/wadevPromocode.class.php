<?php

/**
 * Class wadevPromocode
 *
 * @property wadevPromocodeModel $model
 */
class wadevPromocode extends wadevEntity
{
    public function __construct($model = null)
    {
        if ($model === null) {
            $model = new wadevPromocodeModel;
        }
        parent::__construct($model);
    }

    /**
     * Получает транзакции из API и сохраняет новые в БД
     *
     * @return bool|int
     */
    public static function updateFromApi()
    {
        $api_key = wadevHelper::getApiKey();
        if (!$api_key) {
            return false;
        }
        $last_update = (int)wa('wadev')->getConfig()->getSetting('api.promocode');
        $refresh_rate = (int)wa('wadev')->getConfig()->getSetting('refresh_rate');
        // не прошел еще нужны интервал
        if (time() - $last_update < $refresh_rate * 60) {
            return 0;
        }

        $new_promos = [];

        try {
            $promos = (new wadevWebasystMyApi($api_key))->getPromocode();
            wa('wadev')->getConfig()->setSetting('api.promocode', time());

            $last_promo = wadevPromocodeModel::model()->findLast(1);
            if (!is_null($last_promo)) {
                // todo: check changes
                $promos = array_filter($promos, function ($p) use ($last_promo) {
                    return strtotime($p['create_datetime']) > strtotime($last_promo->create_datetime);
                });
            }

            foreach ($promos as $p) {
                $promo = new self(new wadevPromocodeModel($p));

                if ($promo->model->save()) {
                    foreach ($p['products'] as $slug) {
                        if (!$product_model = wadevProductModel::model()->findBySlug($slug)) {
                            $product_model = new wadevProductModel([
                                'slug' => $slug
                            ]);
                            $product_model->save();
                        }
                        $product = new wadevProduct($product_model);
                        $promo->addProduct($product);
                    }
                    $new_promos[] = $promo;
                }
            }

        } catch (waException $e) {
            // todo do smth
        }

        return count($new_promos);
    }

    /**
     * @param wadevProduct $product
     */
    public function addProduct($product)
    {
        return wadevPromocodeProductsModel::model()->insert([
            'product_id' => $product->model->pk,
            'code_id' => $this->model->pk
        ]);
    }
}