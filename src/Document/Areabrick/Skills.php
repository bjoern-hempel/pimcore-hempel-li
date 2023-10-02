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
use App\Repository\SkillLanguageRepository;
use App\Repository\SkillPersonalRepository;
use App\Repository\SkillProfessionalRepository;
use LogicException;
use Pimcore\Model\Document\Editable\Area\Info;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Skills
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-26)
 * @since 0.1.0 (2023-09-26) First version.
 */
class Skills extends AbstractAreabrick
{
    /**
     * @param SkillLanguageRepository $skillLanguageRepository
     * @param SkillPersonalRepository $skillPersonalRepository
     * @param SkillProfessionalRepository $skillProfessionalRepository
     */
    public function __construct(
        protected SkillLanguageRepository $skillLanguageRepository,
        protected SkillPersonalRepository $skillPersonalRepository,
        protected SkillProfessionalRepository $skillProfessionalRepository
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
        return 'Skills';
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
            'skillLanguages' => $this->skillLanguageRepository->all(),
            'skillPersonals' => $this->skillPersonalRepository->all(),
            'skillProfessionals' => $this->skillProfessionalRepository->all(),
            'locale' => $request->getLocale(),
        ]);

        return null;
    }
}