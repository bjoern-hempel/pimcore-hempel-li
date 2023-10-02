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

namespace App\Service\Entity;

use Exception;
use LogicException;
use Pimcore\Model\DataObject\News;

/**
 * Class NewsService
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-25)
 * @since 0.1.0 (2023-09-25) First version.
 */
class NewsService
{
    /**
     * Sets the seo data if they are not set by editor.
     *
     * @param News $news
     * @return void
     * @throws Exception
     */
    public function setSeo(News $news): void
    {
        if (empty($news->getOgUrl()) || empty($news->getCanonicalUrl())) {
            $linkGenerator = $news->getClass()->getLinkGenerator();

            if (is_null($linkGenerator)) {
                throw new LogicException('No link generator found for news.');
            }

            $url = $linkGenerator->generate($news);

            if (empty($news->getOgUrl())) {
                $news->setOgUrl($url);
            }

            if (empty($news->getCanonicalUrl())) {
                $news->setCanonicalUrl($url);
            }
        }

        if (empty($news->getSeoTitle())) {
            $news->setSeoTitle($news->getTitle());
        }

        if (empty($news->getSeoDescription())) {
            $news->setSeoDescription($news->getShortDescription());
        }
    }
}