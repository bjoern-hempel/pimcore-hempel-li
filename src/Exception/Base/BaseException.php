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

namespace App\Exception\Base;

use Exception;

/**
 * Class BaseException
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-25)
 * @since 0.1.0 (2023-09-25) First version.
 */
abstract class BaseException extends Exception
{
    protected const TEMPLATE_VERBOSE = '%s [%s:%d]';

    /**
     * BaseException constructor.
     */
    public function __construct(protected string $messageNonVerbose)
    {
        $messageVerbose = $this->buildMessageVerbose();

        parent::__construct($messageVerbose);
    }

    /**
     * Gets a non-verbose message.
     *
     * @return string
     */
    public function getMessageNonVerbose(): string
    {
        return $this->messageNonVerbose;
    }

    /**
     * Gets a verbose message.
     *
     * @return string
     */
    public function getMessageVerbose(): string
    {
        return $this->getMessage();
    }

    /**
     * Builds the verbose message.
     *
     * @return string
     */
    protected function buildMessageVerbose(): string
    {
        return sprintf(self::TEMPLATE_VERBOSE, $this->getMessageNonVerbose(), $this->file, $this->line);
    }
}
