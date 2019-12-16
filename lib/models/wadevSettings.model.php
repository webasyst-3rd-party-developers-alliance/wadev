<?php

use SergeR\CakeUtility\Hash;

/**
 * Class wadevSettingsModel
 */
class wadevSettingsModel extends wadevModel
{
    protected $table = 'wadev_settings';
    protected $id = ['contact_id', 'name'];

    /**
     * @param $contact_id
     * @param null $name
     * @param null $default
     * @return array|mixed|null
     * @throws waException
     * @throws Exception
     */
    public function get($name = null, $default = null)
    {
        $contact_id = wa()->getUser()->getId();
        if ($name) {
            return $this->getOne($contact_id, $name, $default);
        }

        $data = (array)$this->getByField('contact_id', $contact_id, true);
        return Hash::combine($data, '{n}.name', '{n}.value');
    }

    /**
     * Выбирает значение единственной настройки
     *
     * @param $contact_id
     * @param $name
     * @param null $default
     * @return mixed|null
     */
    protected function getOne($contact_id, $name, $default = null)
    {
        $data = (array)$this->getById([$contact_id, $name]);
        return ifset($data, 'value', $default);
    }

    /**
     * @param int $contact_id
     * @param string $name
     * @param string $value
     * @return bool|int|resource
     */
    public function set($contact_id, $name, $value)
    {
        $data = ['contact_id' => $contact_id, 'name' => $name, 'value' => $value];
        return $this->insert($data, 1);
    }

    public function del($name)
    {
        $data = [
            'contact_id' => wa()->getUser()->getId(),
            'name'       => $name
        ];
        $this->deleteByField($data);
    }

    /**
     * @param $contact_id
     * @return string
     */
    public function getApiKey($contact_id)
    {
        return $this->getOne($contact_id, 'api_key', '');
    }
}