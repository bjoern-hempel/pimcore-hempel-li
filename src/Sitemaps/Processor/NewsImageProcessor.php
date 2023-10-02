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

namespace App\Sitemaps\Processor;

use LogicException;
use Pimcore\Bundle\SeoBundle\Sitemap\Element\GeneratorContextInterface;
use Pimcore\Bundle\SeoBundle\Sitemap\Element\ProcessorInterface;
use Pimcore\Model\DataObject\News;
use Pimcore\Model\Element\ElementInterface;
use Pimcore\Tool;
use Presta\SitemapBundle\Sitemap\Url\Url;
use Presta\SitemapBundle\Sitemap\Url as SitemapUrl;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class NewsImageProcessor
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-25)
 * @since 0.1.0 (2023-09-25) First version.
 */
readonly class NewsImageProcessor implements ProcessorInterface
{
    /**
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(
        private ParameterBagInterface $parameterBag
    )
    {
    }

    /**
     * Processes the given element (image) to build an url for it.
     *
     * @param Url $url
     * @param ElementInterface $element
     * @param GeneratorContextInterface $context
     * @return Url
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function process(Url $url, ElementInterface $element, GeneratorContextInterface $context): Url
    {
        if (!$element instanceof News) {
            return $url;
        }

        $image = $element->getImage();

        if (is_null($image)) {
            return $url;
        }

        $image = $image->getImage();

        if (is_null($image)) {
            return $url;
        }

        $protocol = $this->parameterBag->get('site_protocol');

        if (!is_string($protocol)) {
            throw new LogicException('The given parameter is not a string.');
        }

        $imageUrl = Tool::getHostUrl($protocol).$image->getRealFullPath();

        $decoratedUrl = new SitemapUrl\GoogleImageUrlDecorator($url);
        $decoratedUrl->addImage(new SitemapUrl\GoogleImage($imageUrl));

        return $decoratedUrl;
    }
}