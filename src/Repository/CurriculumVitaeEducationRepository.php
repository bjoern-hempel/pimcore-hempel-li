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
use Pimcore\Model\DataObject\CurriculumVitaeEducation;

/**
 * Class CurriculumVitaeEducationRepository
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-30)
 * @since 0.1.0 (2023-09-30) First version.
 */
class CurriculumVitaeEducationRepository
{
    /**
     * Finds all CurriculumVitaeEducation entities.
     *
     * @return CurriculumVitaeEducation[]
     */
    public function all(): array
    {
        $curriculumVitaeSection = new CurriculumVitaeEducation\Listing();
        $curriculumVitaeSection->setCondition('path = ?', '/CurriculumVitae/Education/');
        $curriculumVitaeSection->setOrderKey('order');
        $curriculumVitaeSection->setOrder(KeyDb::ASC);

        return $curriculumVitaeSection->load();
    }
}
