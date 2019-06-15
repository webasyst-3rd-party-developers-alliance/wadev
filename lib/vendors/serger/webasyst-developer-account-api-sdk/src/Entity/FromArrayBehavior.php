<?php
/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @copyright Serge Rodovnichenko, 2019
 * @license MIT
 */

namespace SergeR\WebasystDeveloperAccount\Entity;

/**
 * Trait MagicMethods
 * @package SergeR\WebasystDeveloperAccount\Entity
 */
trait FromArrayBehavior
{
    /** @var array */
    protected $_unparsed_items = [];

    /**
     * Sets class properties from key-value array items
     *
     * @param array $data
     */
    protected function _fromArray(array $data = [])
    {
        foreach ($data as $key => $datum) {
            // skip "private"
            if (substr($key, 0, 1) === '_') {
                continue;
            }
            $setter = 'set' . implode('', array_map('ucfirst', explode('_', $key)));
            if (method_exists($this, $setter)) {
                $this->$setter($datum);
                continue;
            }
            if (property_exists($this, $key)) {
                $this->$key = $datum;
                continue;
            }
            $this->_unparsed_items[$key] = $datum;
        }
    }

    /**
     * @return array
     */
    public function getUnparsedItems()
    {
        return $this->_unparsed_items;
    }
}