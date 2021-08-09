<?php

declare(strict_types=1);

namespace Plattry\Config;

use Plattry\Config\Exception\InvalidResourceException;

/**
 * Class Loader
 * @package Plattry\Config
 */
class Loader
{
    /**
     * Load config resource.
     * @param string $resource
     * @param bool $recursive
     * @return array
     * @throws InvalidResourceException
     */
    public function load(string $resource, bool $recursive = false): array
    {
        if (is_dir($resource)) {
            return $this->scan($resource, $recursive);
        } elseif (is_file($resource)) {
            return [$resource];
        } else {
            throw new InvalidResourceException($resource);
        }
    }

    /**
     * Scan directory.
     * @param string $dirname
     * @param bool $recursive
     * @return array
     * @throws InvalidResourceException
     */
    protected function scan(string $dirname, bool $recursive = false): array
    {
        !is_dir($dirname) &&
        throw new InvalidResourceException($dirname);

        $files = [];
        foreach (scandir($dirname) as $filename) {
            if ('.' === $filename || '..' === $filename)
                continue;

            $fullName = $dirname . '/' . $filename;

            if (is_file($fullName))
                $files[] = $fullName;

            if (is_dir($fullName) && true === $recursive)
                array_push($files, ...static::scan($fullName, $recursive));
        }

        return $files;
    }
}
