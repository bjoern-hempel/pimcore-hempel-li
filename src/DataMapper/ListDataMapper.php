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

namespace App\DataMapper;

use App\DataMapper\Base\AbstractDataMapper;
use LogicException;
use Pimcore\Model\DataObject\Concrete;
use Symfony\Component\HttpFoundation\Request;

/**
 * Abstract class AbstractDataMapper
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-25)
 * @since 0.1.0 (2023-09-25) First version.
 */
final class ListDataMapper
{
    /**
     * @param iterable<int, Concrete> $resource
     * @param string $className
     * @param array<int, object> $arguments
     */
    public function __construct(protected iterable $resource, protected string $className, protected array $arguments = [])
    {
    }

    /**
     * Returns the array of resource. Converts all given objects to an array.
     *
     * @param Request $request
     * @return array<int, array<string, mixed>>
     */
    public function getArray(Request $request): array
    {
        $listDataMapper = [];

        foreach ($this->resource as $data) {
            $dataMapper = new $this->className($data, ...$this->arguments);

            $listDataMapper[] = match (true) {
                $dataMapper instanceof AbstractDataMapper => $dataMapper->getArray($request),
                default => throw new LogicException(sprintf('The given class "%s" is not supported by the ListDataMapper.', $this->className)),
            };
        }

        return $listDataMapper;
    }
}