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

namespace App\Document\Areabrick;

use App\Document\Areabrick\Base\AbstractAreabrick;
use App\Repository\CurriculumVitaeEducationRepository;
use App\Repository\CurriculumVitaeEmploymentRepository;
use LogicException;
use Pimcore\Model\Document\Editable\Area\Info;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CurriculumVitae
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-26)
 * @since 0.1.0 (2023-09-26) First version.
 */
class CurriculumVitae extends AbstractAreabrick
{
    /**
     * @param CurriculumVitaeEducationRepository $curriculumVitaeEducationRepository
     * @param CurriculumVitaeEmploymentRepository $curriculumVitaeEmploymentRepository
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    public function __construct(
        protected CurriculumVitaeEducationRepository $curriculumVitaeEducationRepository,
        protected CurriculumVitaeEmploymentRepository $curriculumVitaeEmploymentRepository
    )
    {
    }

    /**
     * Returns the name of the area brick.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'CurriculumVitae';
    }

    /**
     * Adds some listing order keys, the order and the limit.
     *
     * @param Info $info
     * @return Response|null
     */
    public function action(Info $info): ?Response
    {
        $request = $info->getRequest();

        if (is_null($request)) {
            throw new LogicException('Unexpected case for getting request.');
        }

        $info->setParams([
            'employments' => $this->curriculumVitaeEmploymentRepository->all(),
            'educations' => $this->curriculumVitaeEducationRepository->all(),
            'locale' => $request->getLocale(),
        ]);

        return null;
    }
}