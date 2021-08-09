<?php

declare(strict_types=1);

namespace Plattry\Config;

use Plattry\Config\Exception\InvalidResourceException;
use Plattry\Config\Exception\ItemNotFoundException;

/**
 * Class Repository
 * @package Plattry\Config
 */
class Repository
{
    /**
     * Config set
     * @var array
     */
    protected array $set = [];

    /**
     * Config resource loader
     * @var Loader
     */
    protected Loader $loader;

    /**
     * Config resource resolver
     * @var Resolver
     */
    protected Resolver $resolver;

    /**
     * Repository constructor.
     */
    public function __construct()
    {
        $this->loader = new Loader();
        $this->resolver = new Resolver();
    }

    /**
     * Import resource.
     * @param string $resource
     * @param bool $recursive
     * @return void
     * @throws InvalidResourceException
     */
    public function import(string $resource, bool $recursive = false): void
    {
        foreach ($this->loader->load($resource, $recursive) as $file) {
            $this->set = array_merge($this->set, $this->resolver->parse($file));
        }
    }

    /**
     * Determines whether the config option is exist.
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        $set = $this->set;

        while ($point = strpos($name, '.')) {
            $set = $set[substr($name, 0, $point)] ?? false;
            if ($set === false)
                return false;

            $name = substr($name, $point + 1);
        }

        return !($point === 0) && isset($set[$name]);
    }

    /**
     * Get config item.
     * @param string $name
     * @return mixed
     * @throws ItemNotFoundException
     */
    public function get(string $name): mixed
    {
        $origin = $name;
        $set = $this->set;

        do {
            $point = strpos($name, '.');
            $first = $point === false ? $name : substr($name, 0, $point);
            $set = $set[$first] ?? false;
            if ($set === false)
                break;
            if ($point === false)
                return $set;

            $name = substr($name, $point + 1);
        } while (true);

        throw new ItemNotFoundException("$origin");
    }
}
