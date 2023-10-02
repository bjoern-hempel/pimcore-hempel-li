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
 * Class Environment
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-25
 * @since 0.1.0 (2023-09-25) First version.
 */
class Environment
{
    final public const DEVELOPMENT = 'dev';

    final public const DEVELOPMENT_NAME = 'Development environment';

    final public const STAGING = 'staging';

    final public const STAGING_NAME = 'Staging environment';

    final public const PRODUCTION = 'prod';

    final public const PRODUCTION_NAME = 'Production environment';

    final public const TEST = 'test';

    final public const TEST_NAME = 'Test';
}
