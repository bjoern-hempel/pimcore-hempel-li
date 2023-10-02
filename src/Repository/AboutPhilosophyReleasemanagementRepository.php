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
use Pimcore\Model\DataObject\AboutPhilosophyReleasemanagement;

/**
 * Class AboutPhilosophyReleasemanagementRepository
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-27)
 * @since 0.1.0 (2023-09-27) First version.
 * @SuppressWarnings(PHPMD.LongClassName)
 */
class AboutPhilosophyReleasemanagementRepository
{
    /**
     * Finds all AboutPhilosophyReleasemanagement entities.
     *
     * @return AboutPhilosophyReleasemanagement[]
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    public function all(): array
    {
        $aboutPhilosophyReleasemanagements = new AboutPhilosophyReleasemanagement\Listing();
        $aboutPhilosophyReleasemanagements->setCondition('path = ?', '/About/Philosophy/Releasemanagement/');
        $aboutPhilosophyReleasemanagements->setOrderKey('order');
        $aboutPhilosophyReleasemanagements->setOrder(KeyDb::ASC);

        return $aboutPhilosophyReleasemanagements->load();
    }

    /**
     * Finds main AboutPhilosophyReleasemanagement entities.
     *
     * @return AboutPhilosophyReleasemanagement[]
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    public function main(): array
    {
        $aboutPhilosophyReleasemanagements = new AboutPhilosophyReleasemanagement\Listing();
        $aboutPhilosophyReleasemanagements->setCondition('path = ?', '/About/Philosophy/Releasemanagement/');
        $aboutPhilosophyReleasemanagements->setCondition('main = ?', 1);
        $aboutPhilosophyReleasemanagements->setOrderKey('order');
        $aboutPhilosophyReleasemanagements->setOrder(KeyDb::ASC);

        return $aboutPhilosophyReleasemanagements->load();
    }

    /**
     * Finds not main AboutPhilosophyReleasemanagement entities.
     *
     * @return AboutPhilosophyReleasemanagement[]
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    public function notMain(): array
    {
        $aboutPhilosophyReleasemanagements = new AboutPhilosophyReleasemanagement\Listing();
        $aboutPhilosophyReleasemanagements->setCondition('path = ?', '/About/Philosophy/Releasemanagement/');
        $aboutPhilosophyReleasemanagements->setCondition('main = ?', -1);
        $aboutPhilosophyReleasemanagements->setOrderKey('order');
        $aboutPhilosophyReleasemanagements->setOrder(KeyDb::ASC);

        return $aboutPhilosophyReleasemanagements->load();
    }
}
