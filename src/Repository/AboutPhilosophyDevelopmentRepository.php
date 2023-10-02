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

namespace App\Repository;

use App\Constant\KeyDb;
use Pimcore\Model\DataObject\AboutPhilosophyDevelopment;

/**
 * Class AboutPhilosophyDevelopmentRepository
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-26)
 * @since 0.1.0 (2023-09-26) First version.
 */
class AboutPhilosophyDevelopmentRepository
{
    /**
     * Finds all AboutPhilosophyDevelopment entities.
     *
     * @return AboutPhilosophyDevelopment[]
     */
    public function all(): array
    {
        $aboutPhilosophyDevelopments = new AboutPhilosophyDevelopment\Listing();
        $aboutPhilosophyDevelopments->setCondition('path = ?', '/About/Philosophy/Development/');
        $aboutPhilosophyDevelopments->setOrderKey('order');
        $aboutPhilosophyDevelopments->setOrder(KeyDb::ASC);

        return $aboutPhilosophyDevelopments->load();
    }

    /**
     * Finds main AboutPhilosophyDevelopment entities.
     *
     * @return AboutPhilosophyDevelopment[]
     */
    public function main(): array
    {
        $aboutPhilosophyDevelopments = new AboutPhilosophyDevelopment\Listing();
        $aboutPhilosophyDevelopments->setCondition('path = ?', '/About/Philosophy/Development/');
        $aboutPhilosophyDevelopments->setCondition('main = ?', 1);
        $aboutPhilosophyDevelopments->setOrderKey('order');
        $aboutPhilosophyDevelopments->setOrder(KeyDb::ASC);

        return $aboutPhilosophyDevelopments->load();
    }

    /**
     * Finds not main AboutPhilosophyDevelopment entities.
     *
     * @return AboutPhilosophyDevelopment[]
     */
    public function notMain(): array
    {
        $aboutPhilosophyDevelopments = new AboutPhilosophyDevelopment\Listing();
        $aboutPhilosophyDevelopments->setCondition('path = ?', '/About/Philosophy/Development/');
        $aboutPhilosophyDevelopments->setCondition('main = ?', -1);
        $aboutPhilosophyDevelopments->setOrderKey('order');
        $aboutPhilosophyDevelopments->setOrder(KeyDb::ASC);

        return $aboutPhilosophyDevelopments->load();
    }
}
