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

namespace App\Tests\Unit;

use Codeception\Test\Unit;
use LogicException;

/**
 * Class ReadmeTest
 *
 * This is a tongue-in-cheek test created to validate codeception functionality.
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-25)
 * @since 0.1.0 (2023-09-25) First version.
 */
class ReadmeTest extends Unit
{
    private const README_PATH = PROJECT_ROOT . '/README.md';
    private string $readme;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $readmeContent = file_get_contents(self::README_PATH);

        if ($readmeContent === false) {
            throw new LogicException(sprintf('The readme file "%s" does not exist.', self::README_PATH));
        }

        $this->readme = $readmeContent;
    }

    /**
     * @return void
     */
    public function testReadmeIsWrittenWithLove(): void
    {
        self::assertStringContainsString('# Pimcore - www.hempel.li', $this->readme);
        self::assertStringContainsString('## 1. Installation', $this->readme);
    }

    /**
     * @return void
     */
    public function testReadmeContainsInstructionsForExecutingTests(): void
    {
        self::assertStringContainsString('composer test', $this->readme);
    }
}
