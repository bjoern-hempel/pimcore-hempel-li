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

use Pimcore\Model\DataObject\NewsCategory;

/**
 * Class NewsCategoryRepository
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-25)
 * @since 0.1.0 (2023-09-25) First version.
 */
class NewsCategoryRepository
{
    /**
     * Finds the given news with its given identifier.
     *
     * @return NewsCategory[]
     */
    public function all(): array
    {
        $newsCategories = new NewsCategory\Listing();
        $newsCategories->setOrder('ASC');
        $newsCategories->setOrderKey('name');

        return $newsCategories->load();
    }
}
