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

namespace App\DataMapper\Pagination;

use App\DataMapper\Base\AbstractDataMapper;
use Knp\Component\Pager\Pagination\PaginationInterface;
use LogicException;
use Pimcore\Model\DataObject\Concrete;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PaginatorDataMapper
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-25)
 * @since 0.1.0 (2023-09-25) First version.
 * @property PaginationInterface $resource
 */
class PaginatorDataMapper extends AbstractDataMapper
{
    /**
     * @param PaginationInterface<int, Concrete> $resource
     */
    public function __construct(PaginationInterface $resource)
    {
        parent::__construct($resource);
    }

    /**
     * Converts the given ressource to an array.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    protected function toArray(Request $request): array
    {
        if (!method_exists($this->resource, 'getPaginationData')) {
            throw new LogicException('The resource does not have a getPaginationData() method.');
        }

        return $this->resource->getPaginationData();
    }
}
