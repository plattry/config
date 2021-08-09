<?php

declare(strict_types=1);

namespace Plattry\Config\Exception;

use Exception;

/**
 * Class InvalidResourceException
 * @package Plattry\Config\Exception
 */
class InvalidResourceException extends Exception implements ConfigExceptionInterface
{
    /**
     * InvalidResourceException constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct("Invalid resource `$name`");
    }
}
