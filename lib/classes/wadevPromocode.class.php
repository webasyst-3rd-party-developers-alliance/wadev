<?php

/**
 * Class wadevPromocode
 *
 * @property wadevPromocodeModel $model
 * @property wadevProduct        $products
 */
class wadevPromocode extends wadevEntity
{
    /**
     * @var wadevProduct[]
     */
    private $_products;

    public function __construct($model = null)
    {
        if ($model === null) {
            $model = new wadevPromocodeModel;
        }
        parent::__construct($model);
    }

    /**
     * @param $slug
     * @return mixed|wadevProduct
     * @throws waDbException
     */
    public function getProduct($slug)
    {
        if (!is_array($this->_products) || !array_key_exists($slug, $this->_products)) {
            if (!($product_model = wadevProductModel::model()->findBySlug($slug))) {
                $name = explode('/', $slug);
                $product_model = new wadevProductModel([
                    'slug' => $slug,
                    'name' => end($name),
                ]);
                $product_model->save();
            }
            $this->_products[$slug] = new wadevProduct($product_model);
        }

        return $this->_products[$slug];
    }

    /**
     * @return array|wadevProduct[]|null
     * @throws waDbException
     */
    public function getProducts()
    {
        if ($this->_products === null) {
            $this->_products = [];
            $products = (new wadevPromocodeProductsModel)->findByFields('code_id', $this->model->pk, true);
            foreach ($products as $product) {
                $product_model = wadevProductModel::model()->findByPk($product->product_id);
                $this->_products[$product_model->slug] = new wadevProduct($product_model);
            }
        }

        return $this->_products;
    }

    /**
     * Получает транзакции из API и сохраняет новые в БД
     *
     * @return bool|int
     * @throws waException
     */
    public static function updateFromApi()
    {
        $instance = new static();

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
            if ($last_promo !== null) {
                // todo: check changes
                $promos = array_filter($promos, function ($p) use ($last_promo) {
                    return strtotime($p['create_datetime']) > strtotime($last_promo->create_datetime);
                });
            }

            foreach ($promos as $p) {
                $promo = new self(new wadevPromocodeModel($p));

                if ($promo->model->save()) {
                    foreach ($p['products'] as $slug) {
                        $promo->addProduct($instance->getProduct($slug));
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
     * @return bool|int|resource
     * @throws waDbException
     */
    public function addProduct($product)
    {
        return wadevPromocodeProductsModel::model()->insert([
            'product_id' => $product->model->pk,
            'code_id'    => $this->model->pk,
        ]);
    }
}