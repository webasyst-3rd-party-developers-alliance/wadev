<?php
/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @copyright Serge Rodovnichenko, 2019
 * @license MIT
 */

namespace SergeR\WebasystDeveloperAccount\Adapter;

/**
 * Class AbstractAdapter
 *
 * @package SergeR\WebasystDeveloperAccount\Adapter
 */
class AbstractAdapter
{
    /** @var string Base API URL */
    const API_URL = 'https://www.webasyst.ru/my/api/developer/';

    /** @var string Access token */
    protected $token = '';

    /**
     * @return string
     */
    protected function getUrl()
    {
        return self::API_URL;
    }
}