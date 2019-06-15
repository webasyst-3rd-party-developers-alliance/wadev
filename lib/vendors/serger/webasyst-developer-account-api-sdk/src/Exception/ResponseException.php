<?php
/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @copyright Serge Rodovnichenko, 2019
 * @license MIT
 */

namespace SergeR\WebasystDeveloperAccount\Exception;

use Exception;
use RuntimeException;

/**
 * Class ResponseException
 * @package SergeR\WebasystDeveloperAccount\Exception
 */
class ResponseException extends RuntimeException
{
    /** @var ErrorMessage */
    protected $error;

    /**
     * ResponseException constructor.
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($message = "", $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->error = new ErrorMessage();
    }

    /**
     * @param ErrorMessage $err
     * @return $this
     */
    public function setError(ErrorMessage $err)
    {
        $this->error = $err;
        if ($this->error->count() === 1) {
            $this->message = $this->error->getErrors()[0]['message'];
        }

        return $this;
    }
}