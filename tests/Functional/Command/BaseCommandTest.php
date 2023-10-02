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

namespace App\Tests\Functional\Command;

use App\Kernel;
use Ixnode\PhpException\File\FileNotFoundException;
use Ixnode\PhpException\File\FileNotReadableException;
use Ixnode\PhpException\Function\FunctionJsonEncodeException;
use Ixnode\PhpException\Type\TypeInvalidException;
use Ixnode\PhpJsonSchemaValidator\Validator;
use Ixnode\PhpJsonSchemaValidator\ValidatorDebugger;
use JsonException;
use Pimcore\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class BaseCommandTest
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-25)
 * @since 0.1.0 (2023-09-25 First version.
 */
abstract class BaseCommandTest extends KernelTestCase
{
    final public const MESSAGE_JSON_RESPONSE_INVALID = 'The returned json command value does not match with the given schema.';

    final public const MESSAGE_JSON_RESPONSE_VALID = 'The returned json command value unexpectedly matches the specified scheme.';

    private const PIMCORE_CONSOLE = 'PIMCORE_CONSOLE';

    protected CommandTester $cmd;

    /**
     * Returns the name of the command.
     *
     * @return string
     */
    abstract protected function getCommandName(): string;

    /**
     * Returns the kernel class.
     *
     * @return string
     */
    protected static function getKernelClass(): string
    {
        return Kernel::class;
    }

    /**
     * Test setup
     *
     * @return void
     */
    protected function setUp(): void
    {
        /* First, let tests believe that the process is executed as a console application. */
        if (!defined(self::PIMCORE_CONSOLE)) {
            define(self::PIMCORE_CONSOLE, true);
        }

        parent::setUp();

        $application = new Application(self::bootKernel());

        $this->cmd = new CommandTester($application->find($this->getCommandName()));
    }

    /**
     * @param Validator $validator
     * @param string $file
     * @param int $line
     * @return bool
     * @throws JsonException
     * @throws FileNotFoundException
     * @throws FunctionJsonEncodeException
     * @throws TypeInvalidException
     * @throws FileNotReadableException
     */
    protected function validateAndWriteOutput(Validator $validator, string $file, int $line): bool
    {
        $validate = (new ValidatorDebugger($validator, $file, $line))->validate();

        if ($validate === true) {
            return true;
        }

        /* Force output prints from ValidatorDebugger */
        ob_flush();

        return false;
    }
}
