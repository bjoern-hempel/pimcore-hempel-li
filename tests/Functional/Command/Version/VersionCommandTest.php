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

namespace App\Tests\Functional\Command\Version;

use App\Command\Version\VersionCommand;
use App\Constant\CommandSchema;
use App\Tests\Functional\Command\BaseCommandTest;
use Ixnode\PhpContainer\File;
use Ixnode\PhpContainer\Json;
use Ixnode\PhpException\File\FileNotFoundException;
use Ixnode\PhpException\File\FileNotReadableException;
use Ixnode\PhpException\Function\FunctionJsonEncodeException;
use Ixnode\PhpException\Type\TypeInvalidException;
use Ixnode\PhpJsonSchemaValidator\Validator;
use JsonException;

/**
 * Class VersionCommandTest
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-25)
 * @since 0.1.0 (2023-09-25) First version.
 * @link VersionCommand
 */
class VersionCommandTest extends BaseCommandTest
{
    /**
     * Returns the name of the command.
     *
     * @return string
     */
    protected function getCommandName(): string
    {
        return 'version:show';
    }

    /**
     * Test execution.
     *
     * @return void
     * @throws FileNotFoundException
     * @throws FileNotReadableException
     * @throws FunctionJsonEncodeException
     * @throws TypeInvalidException
     * @throws JsonException
     */
    public function testVersionOutputIsCorrect(): void
    {
        /* Arrange */
        $this->cmd->execute(['--format' => 'json']);
        $json = new Json($this->cmd->getDisplay());

        /* Act */
        $validator = new Validator($json, new File(CommandSchema::VERSION_RESOURCE));

        /* Assert */
        $this->assertTrue($this->validateAndWriteOutput($validator, __FILE__, __LINE__), BaseCommandTest::MESSAGE_JSON_RESPONSE_INVALID);
    }
}
