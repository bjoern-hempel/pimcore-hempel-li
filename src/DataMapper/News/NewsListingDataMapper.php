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
use Exception;
use LogicException;
use Pimcore\Model\DataObject\News;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class NewsListingDataMapper
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-25)
 * @since 0.1.0 (2023-09-25) First version.
 * @property News $resource
 */
class NewsListingDataMapper extends AbstractDataMapper
{
    /**
     * Converts the given ressource to an array.
     *
     * @param Request $request
     * @return array<string, mixed>
     * @throws Exception
     */
    protected function toArray(Request $request): array
    {
        $linkGenerator = $this->resource->getClass()->getLinkGenerator();

        if (is_null($linkGenerator)) {
            throw new LogicException('No link generator found for news.');
        }

        $date = $this->resource->getDate();

        if (is_null($date)) {
            throw new LogicException('Date is not set within resource.');
        }

        return [
            'id' => $this->resource->getId(),
            'image' => $this->resource->getImage()?->getImage(),
            'title' => $this->resource->getTitle(),
            'short_description' => $this->resource->getShortDescription(),
            'posted' => $date->setTimezone('Europe/Berlin')->format('F j, Y'),
            'slug' => $linkGenerator->generate($this->resource),
            'tags' => NewsTagDataMapper::listDataMapperFactory($this->resource->getTags())->getArray($request),
        ];
    }
}
