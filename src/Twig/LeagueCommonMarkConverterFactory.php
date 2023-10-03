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

namespace App\Twig;

use App\Renderer\CustomBlockQuoteRenderer;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Extension\Attributes\AttributesExtension;
use League\CommonMark\Extension\CommonMark\Node\Block\BlockQuote;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\CommonMark\Node\Block\Heading;
use League\CommonMark\Extension\CommonMark\Node\Block\ListBlock;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use League\CommonMark\Extension\DefaultAttributes\DefaultAttributesExtension;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Extension\Table\Table;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\Node\Block\Paragraph;

/**
 * @internal
 */
final readonly class LeagueCommonMarkConverterFactory
{
    /**
     * @param ExtensionInterface[] $extensions
     */
    public function __construct(private iterable $extensions)
    {
    }

    /**
     * Invoke magic method.
     *
     * @return CommonMarkConverter
     */
    public function __invoke(): CommonMarkConverter
    {
        $config = [
            'default_attributes' => [
                Heading::class => [
                    'class' => static fn(Heading $node) => match ($node->getLevel()) {
                        1, 2, 3 => ['mb-10', 'mt-10'],
                        4, 5, 6 => ['mb-5', 'mt-5'],
                        default => null,
                    }
                ],
                Paragraph::class => [
                    'class' => ['mb-20', 'mt-10'],
                ],
                FencedCode::class => [
                    'class' => [],
                ],
                Table::class => [
                    'class' => 'table',
                ],
                ListBlock::class => [
                    'class' => ['mb-20', 'mt-10'],
                    'style' => ['list-style-type: square;', 'list-style-position: inside;'],
                ],
                BlockQuote::class => [
                    'class' => ['text-center', 'mb-40', 'mt-40'],
                    'blockquote' => [
                        'class' => 'blockquote',
                    ],
                    'figcaption' => [
                        'class' => 'blockquote-footer',
                    ]
                ],
                Link::class => [
                    'target' => '_blank',
                ]
            ],
        ];

        $converter = new CommonMarkConverter($config);

        /* Add manually some more extensions. */
        $converter->getEnvironment()->addExtension(new TableExtension());
        $converter->getEnvironment()->addExtension(new DefaultAttributesExtension());
        $converter->getEnvironment()->addExtension(new AttributesExtension());

        $converter->getEnvironment()->addRenderer(BlockQuote::class, new CustomBlockQuoteRenderer());

        # Register all given extensions (!tagged_iterator)
        foreach ($this->extensions as $extension) {
            $converter->getEnvironment()->addExtension($extension);
        }

        return $converter;
    }
}

