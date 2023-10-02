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

namespace App\Exception;

use App\Exception\Base\BaseFileException;

/**
 * Class FileNotFoundException
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-25)
 * @since 0.1.0 (2023-09-25) First version.
 */
final class FileNotFoundException extends BaseFileException
{
    public const TEXT_PLACEHOLDER = 'File "%s" not found.';

    /**
     * @param string $path
     */
    public function __construct(string $path)
    {
        $messageNonVerbose = sprintf(self::TEXT_PLACEHOLDER, $path);

        parent::__construct($messageNonVerbose);
    }
}
