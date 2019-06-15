<?php
/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @copyright Serge Rodovnichenko, 2019
 * @license MIT
 */

namespace SergeR\WebasystDeveloperAccount\Entity\Response;

use SergeR\WebasystDeveloperAccount\Entity\PromocodeEntity;

/**
 * Class PromocodeResponse
 * @package SergeR\WebasystDeveloperAccount\Entity\Response
 */
class PromocodeResponse extends PromocodeEntity
{
    public function __construct(array $data)
    {
        $this->_fromArray($data);
    }
}