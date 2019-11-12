<?php

$model = new waModel();
try {
    $model->query('select * from wadev_transaction where contact_id > 0');
} catch (Exception $e) {
    if (!$contact_id = wa()->getUser()->getId()) {
        return;
    }
    foreach (['wadev_transaction', 'wadev_promocode', 'wadev_product'] as $table) {
        $query = 'ALTER TABLE ' . $table . ' ADD `contact_id` INT NOT NULL AFTER `id`';
        $model->query($query);
        $query = 'UPDATE ' . $table . ' SET contact_id=' . $contact_id . ' WHERE 1';
        $model->query($query);
    }
}

try {
    $model->query('select * from wadev_settings');
} catch (Exception $e) {
    if (!$contact_id = wa()->getUser()->getId()) {
        return;
    }
    $query = 'CREATE TABLE wadev_settings (contact_id INT NOT NULL , name VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL , value TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL )';
    $model->query($query);
    $query = 'ALTER TABLE wadev_settings ADD PRIMARY KEY (contact_id, name)';
    $model->query($query);
    $model_app_settings = new waAppSettingsModel();
    $csettings = $model_app_settings->get('wadev');
    foreach ($csettings as $key => $value) {
        $query = 'INSERT INTO wadev_settings (contact_id, name, value) VALUES (' . $contact_id . ', ?, ?);';
        $model->query($query, $key, $value);
    }
    $model_app_settings->del('wadev');
    $model_app_settings->set('wadev', 'update_time', $csettings['update_time']);
    $model_app_settings->clearCache('wadev');
}

