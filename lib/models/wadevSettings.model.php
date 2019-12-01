<?php

class wadevSettingsModel extends wadevModel
{
    protected $table = 'wadev_settings';
    protected $id = ['contact_id', 'name'];

    public function get($name = null, $default = null)
    {
        $settings = [];
        $query = 'select name,value from ' . $this->table . ' where contact_id=?';
        $data = $this->query($query, wa()->getUser()->getId())->fetchAll();
        foreach ($data as $s) {
            $settings[$s['name']] = $s['value'];
        }
        if($name){
            return ifset($settings[$name], $default);
        } else {
            return $settings;
        }
    }

    public function set($name, $value)
    {
        $data = ['contact_id' => wa()->getUser()->getId(), 'name' => $name, 'value' => $value];
        return $this->insert($data, 1);
    }

    public function del($name)
    {
        $data = [
            'contact_id' => wa()->getUser()->getId(),
            'name' => $name
        ];
        $this->deleteByField($data);
    }
}