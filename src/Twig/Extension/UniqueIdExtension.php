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

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class UniqueIdExtension
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-25)
 * @since 0.1.0 (2023-09-25) First version.
 */
class UniqueIdExtension extends AbstractExtension
{
    /**
     * Returns the functions needed to be registered to Twig templates.
     *
     * @return TwigFunction[]
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('uniqueId', fn(string $prefix = '', bool $moreEntropy = false) => uniqid($prefix, $moreEntropy)),
        ];
    }
}