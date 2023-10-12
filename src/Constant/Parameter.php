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
 * Class Parameter
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-25)
 * @since 0.1.0 (2023-09-25) First version.
 */
class Parameter
{
    final public const EMAIL_RECIPIENTS_ERROR = 'email.recipients_error';

    final public const ENVIRONMENT_HOST_NAME = 'environment.host_name';

    final public const ENVIRONMENT_GOOGLE_API_KEY = 'environment.google_api_key';

    final public const MAILER_DNS = 'mailer.dns';
}
