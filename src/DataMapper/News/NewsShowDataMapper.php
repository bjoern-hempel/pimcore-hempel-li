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

use App\Constant\Timezone;
use App\DataMapper\Base\AbstractDataMapper;
use LogicException;
use Pimcore\Model\DataObject\News;
use Pimcore\Model\User;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class NewsShowDataMapper
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-25)
 * @since 0.1.0 (2023-09-25) First version.
 * @property News $resource
 */
class NewsShowDataMapper extends AbstractDataMapper
{
    /**
     * Converts the given ressource to an array.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    protected function toArray(Request $request): array
    {
        $postedById = (int) $this->resource->getPostedBy();

        $postedBy = User::getById($postedById);

        if (is_null($postedBy)) {
            throw new LogicException('User not found');
        }

        $seoImage = $this->resource->getSeoImage();

        if (empty($seoImage)) {
            $seoImage = $this->resource->getImage();
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
            'content_markdown' => $this->resource->getContentMarkdown(),
            'posted' => $date->setTimezone(Timezone::EUROPE_BERLIN)->format('F j, Y'),
            'postedBy' => $postedBy->getFirstname(),
            'about' => $this->resource->getAbout(),
            'ad' => $this->resource->getAd(),
            'canonical_url' => $this->resource->getCanonicalUrl(),
            'seo_title' => $this->resource->getSeoTitle(),
            'seo_description' => $this->resource->getSeoDescription(),
            'og_url' => $this->resource->getOgUrl(),
            'og_locale' => $this->resource->getOgLocale(),
            'seo_image' => $seoImage?->getImage(),
            'tags' => NewsTagDataMapper::listDataMapperFactory($this->resource->getTags())->getArray($request),
            'related_news' => NewsRelatedDataMapper::listDataMapperFactory($this->resource->getRelatedNews())->getArray($request),
        ];
    }
}
