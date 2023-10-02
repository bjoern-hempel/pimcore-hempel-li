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

namespace App\Website\LinkGenerator;

use InvalidArgumentException;
use LogicException;
use Pimcore\Model\DataObject\ClassDefinition\LinkGeneratorInterface;
use Pimcore\Model\DataObject\News;
use Pimcore\Tool;
use Pimcore\Twig\Extension\Templating\PimcoreUrl;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Class NewsLinkGenerator
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-25)
 * @since 0.1.0 (2023-09-25) First version.
 */
readonly class NewsLinkGenerator implements LinkGeneratorInterface
{
    /**
     * @param SluggerInterface $slugger
     * @param PimcoreUrl $pimcoreUrl
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(
        private SluggerInterface $slugger,
        private PimcoreUrl $pimcoreUrl,
        private ParameterBagInterface $parameterBag
    )
    {
    }

    /**
     * Generates a link from given news entity.
     *
     * @param object $object
     * @param array<int|string, mixed> $params
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function generate(object $object, array $params = []): string
    {
        if (!$object instanceof News) {
            throw new InvalidArgumentException('Given object is not a News.');
        }

        $slug = $this->slugger->slug((string) $object->getTitle());

        $link = ($this->pimcoreUrl)(
            [
                'slug' => $slug,
                'newsId' => $object->getId(),
            ],
            'news_show',
            true
        );

        if (!str_contains($link, 'https://') && !str_contains($link, 'http://')) {
            $protocol = $this->parameterBag->get('site_protocol');

            if (!is_string($protocol)) {
                throw new LogicException('The given parameter is not a string.');
            }

            $link = sprintf('%s%s', Tool::getHostUrl($protocol), $link);
        }

        return $link;
    }
}