<?php

/**
 * Class wadevEntity
 *
 * @property wadevModelExt $model
 */
class wadevEntity
{
    protected $model;

    /**
     * @param $name
     *
     * @return mixed
     */
    protected function formatMethodName($name)
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $name)));
    }

    public function __get($name)
    {
        $method = 'get' . $this->formatMethodName($name);
        if (method_exists($this, $method)) {
            return $this->$method();
        }
        throw new waDbException('Invalid attribute');
    }

    public function __set($name, $value)
    {
        $method = 'set' . $this->formatMethodName($name);
        if (method_exists($this, $method)) {
            return $this->$method($value);
        }
    }

    public function __construct($model = null)
    {
        $this->setModel($model);
    }

    /**
     * @param $model wadevModelExt
     *
     * @return static
     */
    protected function setModel($model)
    {
        if ($model instanceof wadevModelExt) {
            $this->model = $model;
        }

        return $this;
    }

    /**
     * @return static
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param $models array
     *
     * @return static[]
     */
    public static function generate($models)
    {
        $obj = [];
        if (!$models) {
            return $obj;
        }
        foreach ($models as $model) {
            if ($model instanceof wadevModelExt) {
                $obj[] = new static($model);
            }
        }

        return $obj;
    }

}