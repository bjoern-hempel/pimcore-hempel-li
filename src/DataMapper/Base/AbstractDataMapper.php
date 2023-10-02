<?php

/*
 * This file is part of the https://github.com/bjoern-hempel/pimcore-hempel-li.git project.
 *
 * (c) 2023 Björn Hempel <bjoern@hempel.lil>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\DataMapper\Base;

use App\DataMapper\ListDataMapper;
use InvalidArgumentException;
use Pimcore\Model\DataObject\Concrete;
use Symfony\Component\HttpFoundation\Request;

/**
 * Abstract class AbstractDataMapper
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-25)
 * @since 0.1.0 (2023-09-25) First version.
 */
abstract class AbstractDataMapper
{
    /**
     * @param array<int, object>|object $resource
     */
    public function __construct(protected array|object $resource)
    {
    }

    /**
     * Converts the given ressource to an array (conversion map).
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    abstract protected function toArray(Request $request): array;

    /**
     * Returns the array of resource.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function getArray(Request $request): array
    {
        /* if $this->resource is an array and is empty: count($this->resource) === 0 */
        if (is_array($this->resource) && count($this->resource) <= 0) {
            return $this->resource;
        }

        $result = [];

        $data = $this->toArray($request);

        if (count($data) <= 0) {
            return $data;
        }

        /* Translate an array of AbstractDataMapper objects into an array */
        foreach ($data as $fieldName => $value) {
            $result[$fieldName] = match (true) {
                $value instanceof self => $value->getArray($request),
                default => $value,
            };
        }

        return $result;
    }

    /**
     * Creates a new ListingDataMapper instance from given array list.
     *
     * @param iterable<int, Concrete> $list
     * @return ListDataMapper
     */
    public static function listDataMapperFactory(iterable $list): ListDataMapper
    {
        $arguments = func_get_args();

        array_shift($arguments);

        $argumentsNew = [];

        foreach ($arguments as $argument) {
            if (!is_object($argument)) {
                throw new InvalidArgumentException('Argument must be an object.');
            }

            $argumentsNew[] = $argument;
        }

        return new ListDataMapper($list, static::class, $argumentsNew);
    }

    /**
     * Call method from ressource class.
     *
     * @param string $name
     * @param mixed $arguments
     * @return mixed
     */
    public function __call(string $name, mixed $arguments): mixed
    {
        $callable = [$this->resource, $name];

        if (!is_callable($callable)) {
            throw new InvalidArgumentException(sprintf('Method "%s" does not callable within given ressource.', $name));
        }

        if (!is_array($arguments)) {
            throw new InvalidArgumentException('Arguments must be an array.');
        }

        return call_user_func_array($callable, $arguments);
    }

    /**
     * Returns a property from ressource class.
     *
     * @param string $name
     * @return mixed
     */
    public function __get(string $name): mixed
    {
        return $this->resource->{$name};
    }
}