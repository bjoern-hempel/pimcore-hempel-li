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
use Pimcore\Model\DataObject\WorkCategory;

/**
 * Class WorkCategoryRepository
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-30)
 * @since 0.1.0 (2023-09-30) First version.
 */
class WorkCategoryRepository
{
    /**
     * Finds all WorkCategory entities.
     *
     * @return WorkCategory[]
     */
    public function all(): array
    {
        $skillLanguages = new WorkCategory\Listing();
        $skillLanguages->setCondition('path = ?', '/Work/Categories/');
        $skillLanguages->setOrderKey('order');
        $skillLanguages->setOrder(KeyDb::ASC);

        return $skillLanguages->load();
    }
}
