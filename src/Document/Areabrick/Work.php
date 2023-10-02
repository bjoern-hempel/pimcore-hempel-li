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
use App\Repository\WorkCategoryRepository;
use App\Repository\WorkRepository;
use LogicException;
use Pimcore\Model\Document\Editable\Area\Info;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Work
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-26)
 * @since 0.1.0 (2023-09-26) First version.
 */
class Work extends AbstractAreabrick
{
    /**
     * @param WorkRepository $workRepository
     * @param WorkCategoryRepository $workCategoryRepository
     */
    public function __construct(protected WorkRepository $workRepository, protected WorkCategoryRepository $workCategoryRepository)
    {
    }

    /**
     * Returns the name of the area brick.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'Work';
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
            'works' => $this->workRepository->all(),
            'workCategories' => $this->workCategoryRepository->all(),
            'locale' => $request->getLocale(),
        ]);

        return null;
    }
}