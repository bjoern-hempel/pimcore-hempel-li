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

namespace App\Renderer;

use League\CommonMark\Extension\CommonMark\Node\Block\BlockQuote;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;
use League\CommonMark\Xml\XmlNodeRendererInterface;
use LogicException;
use Stringable;

class CustomBlockQuoteRenderer implements NodeRendererInterface, XmlNodeRendererInterface
{
    /**
     * {@inheritDoc}
     *
     * @param BlockQuote $node
     * @psalm-suppress MoreSpecificImplementedParamType
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): Stringable
    {
        BlockQuote::assertInstanceOf($node);

        $attributes = $node->data->get('attributes');

        if (!is_array($attributes)) {
            throw new LogicException('Given attributes must be an array.');
        }

        $filling = $childRenderer->renderNodes($node->children());
        $innerSeparator = $childRenderer->getInnerSeparator();

        $author = null;
        $blockquoteAttrs = [];
        $figcaptionAttrs = [];

        if (array_key_exists('blockquote', $attributes)) {
            $blockquoteAttrs = $attributes['blockquote'];
            unset($attributes['blockquote']);
        }

        if (array_key_exists('figcaption', $attributes)) {
            $figcaptionAttrs = $attributes['figcaption'];
            unset($attributes['figcaption']);
        }

        if (array_key_exists('author', $attributes)) {
            $author = $attributes['author'];
            unset($attributes['author']);
        }

        $figcaption = is_null($author) ? '' : new HtmlElement(
            'figcaption',
            $figcaptionAttrs,
            sprintf('&mdash; %s &mdash;', $author)
        );

        $blockquote = new HtmlElement(
            'blockquote',
            $blockquoteAttrs,
            $innerSeparator . $filling . $innerSeparator
        );

        if ($filling === '') {
            return new HtmlElement('blockquote', $attributes, $innerSeparator);
        }

        return new HtmlElement('figure', $attributes, $blockquote.$figcaption);
    }

    /**
     * @param Node $node
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getXmlTagName(Node $node): string
    {
        return 'block_quote';
    }

    /**
     * @param BlockQuote $node
     * @return array<string, scalar>
     * @psalm-suppress MoreSpecificImplementedParamType
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getXmlAttributes(Node $node): array
    {
        return [];
    }
}