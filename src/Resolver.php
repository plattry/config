<?php

declare(strict_types=1);

namespace Plattry\Config;

use Plattry\Config\Exception\InvalidResourceException;

/**
 * Class Resolver
 * @package Plattry\Config
 */
class Resolver
{
    /**
     * Parse config file.
     * @param string $filename
     * @return array
     * @throws InvalidResourceException
     */
    public function parse(string $filename): array
    {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        !$this->valid($ext) &&
        throw new InvalidResourceException(".$ext");

        return match ($ext) {
            'ini' => $this->parseIni($filename),
            'yam', 'yaml' => $this->parseYaml($filename)
        };
    }

    /**
     * Parse .ini file.
     * @param string $filename
     * @return array
     * @throws InvalidResourceException
     */
    protected function parseIni(string $filename): array
    {
        $content = parse_ini_file($filename, true);

        !is_array($content) &&
        throw new InvalidResourceException("$filename");

        return $content;
    }

    /**
     * Parse .yaml file.
     * @param string $filename
     * @return array
     * @throws InvalidResourceException
     */
    protected function parseYaml(string $filename): array
    {
        $content = yaml_parse_file($filename);

        !is_array($content) &&
        throw new InvalidResourceException("$filename");

        return $content;
    }

    /**
     * Validate file type.
     * @param string $extname
     * @return bool
     */
    protected function valid(string $extname): bool
    {
        return in_array($extname, ['ini', 'yam', 'yaml']);
    }
}
