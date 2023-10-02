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

namespace App\DataMapper\News;

use App\DataMapper\Base\AbstractDataMapper;
use Pimcore\Model\DataObject\NewsCategory;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class NewsCategoryDataMapper
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-25)
 * @since 0.1.0 (2023-09-25) First version.
 * @property NewsCategory $resource
 */
class NewsCategoryDataMapper extends AbstractDataMapper
{
    /**
     * Converts the given ressource to an array.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    protected function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->getId(),
            'name' => $this->resource->getName(),
        ];
    }
}
