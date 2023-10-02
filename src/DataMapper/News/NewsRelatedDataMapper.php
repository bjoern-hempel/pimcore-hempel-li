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

use App\Constant\KeyTwig;
use App\Constant\Timezone;
use App\DataMapper\Base\AbstractDataMapper;
use Exception;
use LogicException;
use Pimcore\Model\DataObject\News;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class NewsRelatedDataMapper
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-25)
 * @since 0.1.0 (2023-09-25) First version.
 * @property News $resource
 */
class NewsRelatedDataMapper extends AbstractDataMapper
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
            KeyTwig::ID => $this->resource->getId(),
            KeyTwig::IMAGE => $this->resource->getImage()?->getImage(),
            KeyTwig::TITLE => $this->resource->getTitle(),
            KeyTwig::SHORT_DESCRIPTION => $this->resource->getShortDescription(),
            KeyTwig::POSTED => $date->setTimezone(Timezone::EUROPE_BERLIN)->format('F j, Y'),
            KeyTwig::SLUG => $linkGenerator->generate($this->resource),
            KeyTwig::TAGS => NewsTagDataMapper::listDataMapperFactory($this->resource->getTags())->getArray($request),
        ];
    }
}
