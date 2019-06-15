<?php
/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @copyright Serge Rodovnichenko, 2019
 * @license MIT
 */

namespace SergeR\WebasystDeveloperAccount\Exception;

use Countable;

/**
 * Class ErrorMessageDecoder
 *
 * Декодирует сообщение об ошибке API
 *
 * @package SergeR\WebasystDeveloperAccount\Exception
 */
class ErrorMessage implements Countable
{
    protected $errors = [];

    public function __construct(array $content = [])
    {
        if (!isset($content['errors'])) {
            return;
        }

        if (is_string($content['errors'])) {
            $this->errors[] = ['code' => 0, 'message' => $content['errors']];
        } elseif (is_array($content['errors'])) {
            $errors = $content['errors'];
            if (isset($errors[0]) && !is_array($errors[0])) {
                if (isset($errors[1])) {
                    $this->errors[] = ['code' => $errors[0], 'message' => $errors[1]];
                } else {
                    $this->errors[] = ['code' => 0, 'message' => $errors[0]];
                }
            } else {
                foreach ($errors as $item) {
                    if (!is_array($item)) {
                        $this->errors[] = ['code' => 0, 'message' => (string)$item];
                    } else {
                        if (isset($item[0])) {
                            if (isset($item[1])) {
                                $this->errors[] = ['code' => $item[0], 'message' => $item[1]];
                            } else {
                                $this->errors[] = ['code' => 0, 'message' => (string)$item[0]];
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Count errors
     */
    public function count()
    {
        return count($this->errors);
    }

    /**
     * @return array<array{code: int|string, message: string}>
     */
    public function getErrors()
    {
        return $this->errors;
    }
}