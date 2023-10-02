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

namespace App\Sitemaps;

use Exception;
use LogicException;
use Pimcore\Bundle\SeoBundle\Sitemap\Element\AbstractElementGenerator;
use Pimcore\Bundle\SeoBundle\Sitemap\Element\GeneratorContext;
use Pimcore\Model\DataObject\News;
use Presta\SitemapBundle\Service\UrlContainerInterface;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class NewsGenerator
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-25)
 * @since 0.1.0 (2023-09-25) First version.
 */
class NewsGenerator extends AbstractElementGenerator
{
    /**
     * Builds links for all news (public/sitemap.news.xml).
     *
     * @param UrlContainerInterface $urlContainer
     * @param string|null $section
     * @return void
     * @throws Exception
     */
    public function populate(UrlContainerInterface $urlContainer, string $section = null): void
    {
        /* Do not add entries if section doesn't match */
        if (null !== $section && $section !== 'news') {
            return;
        }

        $section = 'news';

        $list = new News\Listing();
        $list->setOrderKey('date');
        $list->setOrder('DESC');

        $context = new GeneratorContext($urlContainer, $section);

        /** @var News $news */
        foreach ($list as $news) {
            /* Only add element if it is not filtered */
            if (!$this->canBeAdded($news, $context)) {
                continue;
            }

            $linkGenerator = $news->getClass()->getLinkGenerator();

            if (is_null($linkGenerator)) {
                throw new LogicException(sprintf('Class "%s" does not have a link generator.', $news::class));
            }

            /* Use a link generator to generate a URL to the news. You need to make sure the link generator
             * generates an absolute url.
             */
            $link = $linkGenerator->generate($news, [
                'referenceType' => UrlGeneratorInterface::ABSOLUTE_URL
            ]);

            /* Create an entry for the sitemap */
            $url = new UrlConcrete($link);

            /* Run url through processors */
            $url = $this->process($url, $news, $context);

            /* Processors can return null to exclude the url */
            if (null === $url) {
                continue;
            }

            /* Add the url to the container */
            $urlContainer->addUrl($url, $section);
        }
    }
}
