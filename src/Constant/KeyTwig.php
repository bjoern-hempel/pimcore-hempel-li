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

namespace App\Constant;

/**
 * Class KeyTwig
 *
 * Contains array keys separated with "_" (for twig templates).
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-25)
 * @since 0.1.0 (2023-09-25) First version.
 */
class KeyTwig
{
    final public const ID = 'id';

    final public const IMAGE = 'image';

    final public const POSTED = 'posted';

    final public const SHORT_DESCRIPTION = 'short_description';

    final public const SLUG = 'slug';

    final public const TAGS = 'tags';

    final public const TITLE = 'title';
}
