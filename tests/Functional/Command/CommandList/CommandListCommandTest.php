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

namespace App\Tests\Functional\Command\CommandList;

use App\Tests\Functional\Command\BaseCommandTest;

/**
 * Class CommandListCommandTest
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-25)
 * @since 0.1.0 (2023-09-25) First version.
 */
class CommandListCommandTest extends BaseCommandTest
{
    /**
     * Returns the name of the command.
     *
     * @return string
     */
    protected function getCommandName(): string
    {
        return 'list';
    }

    /**
     * Test execution.
     *
     * @return void
     */
    public function testPimcoreCommandsAppearInListing(): void
    {
        /* Arrange */
        $this->cmd->execute([]);

        /* Act */

        /* Assert */
        self::assertStringContainsString('pimcore:', $this->cmd->getDisplay());
    }
}
