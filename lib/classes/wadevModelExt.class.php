<?php

/**
 * Class waModelExt
 *
 * @property integer|array $pk
 */
class wadevModelExt extends waModel
{
    /**
     * @var waArrayObjectDiff for holding values
     */
    protected $attributes;
    protected $_dirty;
    protected $_pk;
    protected $_required_attributes = [];
    protected $_errors;

    public $isNewRecord;

    /**
     * waModelExt constructor.
     *
     * @param array $attributes
     * @param null  $type
     * @param bool  $writable
     *
     * @throws waDbException
     */
    public function __construct($attributes = [], $type = null, $writable = false)
    {
        parent::__construct($type, $writable);
        $this->_pk = $this->id;

//        if (is_array($this->id)) {
//            throw new waDbException('Can`t be used with composite primary key');
//        }

        $this->isNewRecord = true;
        $this->_dirty = [];

        $this->attributes = new waArrayObjectDiff();

        $this->fillAttributes();
        if (!is_array($attributes)) {
            $attributes = [];
        }
        $this->setAttributes(array_merge($this->getDefaultAttributes(), $attributes));
    }

    /**
     * @return array
     */
    protected function getDefaultAttributes()
    {
        $default = [];
        foreach ($this->getMetadata() as $name => $field) {
            if (array_key_exists('default', $field)) {
                $default[$name] = $field['default'];
            } elseif (!empty($field['null'])) {
                $default[$name] = null;
            } else {
                $default[$name] = '';
            }
        }

        return $default;
    }

    /**
     * Returns table primary key value (only non-composite pk)
     * @return mixed|null
     * @throws waDbException
     */
    public function getPk()
    {
        if (is_array($this->id)) {
            $pk = [];
            foreach ($this->id as $id) {
                $pk[$id] = $this->getAttribute($id);
            }

            return $pk;
        }

        return $this->getAttribute($this->_pk);
    }

    protected function getLastInsertedComplexId()
    {
        return false;
    }

    /**
     * @param $value
     *
     * @throws waDbException
     */
    protected function setPk($value)
    {
        if (is_array($this->id)) {
            if ($complex_value = $this->getLastInsertedComplexId()) {
                $value = $complex_value;
            }
            if ($this->id != array_keys($value)) {
                throw new waException('can`t set primary key');
            }
            foreach ($this->id as $id) {
                $this->setAttribute($id, $value[$id]);
            }
        } elseif ($value === true) {
            $this->setAttribute($this->_pk, $this->getLastInsertedComplexId());
        } else {
            $this->setAttribute($this->_pk, $value);
        }
    }

    /**
     * Fills internal attributes array with table fields names.
     * @return array
     */
    protected function fillAttributes()
    {
        $this->attributes->clearPersistent();
        if ($this->fields) {
            $this->attributes->setAll(array_fill_keys(array_keys($this->fields), ''));
        }

        return $this->attributes;
    }

    /**
     * Checks if model has such attribute
     *
     * @param $name
     *
     * @return bool
     */
    public function hasAttribute($name)
    {
        return $this->attributes->offsetExists($name);
    }

    protected function castPopulated($field, $value)
    {
        if (!isset($this->fields[$field])) {
            return $value;
        }
        if (!isset($this->fields[$field]['type'])) {
            return $value;
        }
        $type = strtolower($this->fields[$field]['type']);

        switch ($type) {
            case 'bigint':
            case 'tinyint':
            case 'int':
            case 'integer':
                return (int)$value;
            case 'decimal':
            case 'double':
            case 'float':
                return (double)$value;
            default:
                return $value;
        }
    }

    /**
     * @param array $attributes
     *
     * @throws waDbException
     */
    public function setAttributes($attributes = [])
    {
        if (!$this->attributes->count()) {
            $this->fillAttributes();
        }

        foreach ($attributes as $attribute_key => $attribute_value) {
            try {
//                if (isset($this->$attribute_key)) {
//                    $this->setAttribute($attribute_key, $attribute_value);
//                } else {
                if ($this->hasAttribute($attribute_key)) {
                    $this->attributes[$attribute_key] = $this->castPopulated($attribute_key, $attribute_value);
                }
            } catch (Exception $ex) {
                throw new waDbException($ex->getMessage());
            }
        }
    }

    /**
     * @param $name
     *
     * @return mixed
     * @throws waDbException
     */
    public function getAttribute($name)
    {
        if ($this->hasAttribute($name)) {
            return $this->attributes[$name];
        }
        throw new waDbException('No attribute ' . $name);
    }

    public function __isset($name)
    {
        return $this->hasAttribute($name);
    }

    public function __unset($name)
    {
        if ($this->hasAttribute($name)) {
            unset($this->attributes[$name]);
        }
    }

    /**
     * @return array
     * @throws waDbException
     */
    public function getAttributes()
    {
        $attrs = [];
        foreach ($this->attributes as $name => $attr) {
            if (isset($this->$name)) { /* just in case if there is 'id' field or smth like this */
                $attrs[$name] = $this->getAttribute($name);
            } else {
                $attrs[$name] = $this->$name;
            }
        }

        return $attrs;
    }

    protected function formatMethodName($name)
    {
        return str_replace(' ', '', ucwords($name, '_'));
    }

    protected function setMethodName($name)
    {
        return 'set' . $this->formatMethodName($name);
    }

    protected function getMethodName($name)
    {
        return 'get' . $this->formatMethodName($name);
    }

    public function setAttribute($name, $value)
    {
        $method = $this->setMethodName($name);
        if (method_exists($this, $method)) {
            $this->$method($value);
        } elseif ($this->hasAttribute($name)) {
            $this->attributes[$name] = $value;
        }
//        else {
//            $this->_dirty[$name] = $value; //todo: hm.. sure?
//        }
    }

    /**
     * @param $name
     *
     * @return mixed
     * @throws waDbException
     */
    public function __get($name)
    {
        $method = $this->getMethodName($name);
        if (method_exists($this, $method)) {
            return $this->$method();
        }
        if ($this->hasAttribute($name)) {
            return $this->attributes[$name]; //todo: cast to $this->fields[$name]['type']
        }
        if (array_key_exists($name, $this->_dirty)) {
            return $this->_dirty[$name];
        }
        throw new waDbException('Invalid attribute: ' . $name);
    }

    public function __set($name, $value)
    {
        return $this->setAttribute($name, $value);
    }

    /**
     * @return static
     */
    public static function model()
    {
        return new static();
    }

    public static function generateModels($vals, $one = false)
    {
        $models = self::populate($vals);
        if ($models && $one) {
            $models = reset($models);
        }

        return $models ? $models : null;
    }

    /**
     * Return model/models
     *
     * @param $query waDbQuery|waDbResultSelect
     * @param $one bool will return only first model
     *
     * @return static|static[]|null
     */
    public function findByQuery($query, $one = true)
    {
        $vals = $query->fetchAll();
        if (!$vals) {
            return null;
        }

        return self::generateModels($vals, $one);
    }

    /**
     * Return model/models
     *
     * @param $query waDbQuery|waDbResultSelect
     * @param $one bool will return only first model
     *
     * @return static|static[]|null
     */
    public function findByFields($field, $value = null, $all = false, $limit = false)
    {
        $vals = $this->getByField($field, $value, $all, $limit);
        // from getByField =\
        if (is_array($field)) {
            $all = $value;
        }
        if (!$all) {
            $vals = [$vals];
        }
        if (!$vals) {
            return null;
        }

        return self::generateModels($vals, !$all);
    }

    /**
     * Return model/models
     *
     * @param $pk int|array
     *
     * @return static|static[]|null
     */
    public function findByPk($pk)
    {
        $one = false;
        if (!is_array($pk)) {
            $one = true;
        }

        $vals = $this->getById($pk);
        if (!$vals) {
            return null;
        }

        if ($one) {
            $vals = [$vals];
        }

        return self::generateModels($vals, $one);
    }

    /**
     * @return null|static|static[]
     */
    public function findAll()
    {
        $query = new waDbQuery($this);

        return $this->findByQuery($query->select('*'), false);
    }

    /**
     * Returns models with db data
     *
     * @param $vals array
     *
     * @return static[]|null
     */
    public static function populate($vals)
    {
        $return = [];

        if (!$vals) {
            return null;
        }
        foreach ($vals as $val) {
            if (!$val) {
                continue;
            }
            try {
                $model = new static($val);
                $model->isNewRecord = false;
                $return[] = $model;
            } catch (waDbException $ex) {

            }
        }

        return $return ? $return : null;
    }

    /**
     * @param $json
     *
     * @throws waDbException
     */
    public function loadFromJson($json)
    {
        $data = json_decode($json, 1);
        $this->setAttributes($data);
    }

    protected function beforeSave()
    {
        return true;
    }

    protected function afterSave($new = false)
    {
        return true;
    }

    /**
     * @param array $attributes
     * @param int   $type Execution mode for SQL query INSERT:
     *     0: query is executed without additional conditions (default mode)
     *     1: query is executed with condition ON DUPLICATE KEY UPDATE
     *     2: query is executed with key word IGNORE
     *
     * @return bool|int|null|resource|waDbResultUpdate
     * @throws waDbException
     */
    public function save($validate = true, $attributes = [], $type = 0)
    {
        if (is_array($validate)) {
            $type = $attributes;
            $attributes = $validate;
            $validate = true;
        }

        if (!$this->beforeSave()) {
            return false;
        }

        $save_attributes = $this->getAttributes();

        if ($validate && !$this->validate($attributes)) {
            return false;
        }

        $new = $this->isNewRecord;
        if ($new) {
            $result = $this->insert($save_attributes, $type);
            if ($result) {
                $this->setPk($result);
                $this->isNewRecord = false;
            }
        } else {
            if ($attributes) {
                foreach ($save_attributes as $attribute => $value) {
                    if (!in_array($attribute, $attributes)) {
                        unset($save_attributes[$attribute]);
                    }
                }
            }
            $result = $this->updateById($this->getPk(), $save_attributes);
        }

        if ($result) {
            $this->afterSave($new);
        }

        return $result;
    }

    /**
     * @return bool
     * @throws waDbException
     */
    public function delete()
    {
        return $this->deleteById($this->getPk());
    }

    public function getRequiredAttributes()
    {
        return $this->_required_attributes;
    }

    /**
     * @param $attributes
     *
     * @return bool
     * @throws waDbException
     */
    public function validate($attributes = [])
    {
        $save_attributes = $this->getAttributes();
        foreach ($this->_required_attributes as $required_attribute) {
            if (empty($save_attributes[$required_attribute])
                || ($attributes && !array_key_exists($required_attribute, $attributes))) {
                throw new waDbException('no required attribute ' . $required_attribute);
            }
        }
        return true;
    }

    public function deleteByContactId()
    {
        return $this->deleteByField('contact_id', wa()->getUser()->getId());
    }
}