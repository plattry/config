<?php

declare(strict_types=1);

namespace Plattry\Config\Exception;

use Exception;

/**
 * Class ItemNotFoundException
 * @package Plattry\Config\Exception
 */
class ItemNotFoundException extends Exception implements ConfigExceptionInterface
{
    /**
     * ItemNotFoundException constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct("Not found configuration item `$name`.");
    }
}
