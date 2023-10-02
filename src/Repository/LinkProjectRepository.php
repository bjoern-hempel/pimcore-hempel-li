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
use Pimcore\Model\DataObject\LinkProject;

/**
 * Class LinkProjectRepository
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-26)
 * @since 0.1.0 (2023-09-26) First version.
 */
class LinkProjectRepository
{
    /**
     * Finds the given news with its given identifier.
     *
     * @return LinkProject[]
     */
    public function all(): array
    {
        $linkProjects = new LinkProject\Listing();
        $linkProjects->setCondition('path = ?', '/Link/Projects/');
        $linkProjects->setOrderKey('order');
        $linkProjects->setOrder(KeyDb::ASC);

        return $linkProjects->load();
    }
}
