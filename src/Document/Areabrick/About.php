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
use App\Repository\AboutPhilosophyDevelopmentRepository;
use App\Repository\AboutPhilosophyReleasemanagementRepository;
use App\Repository\LinkProjectRepository;
use LogicException;
use Pimcore\Model\Document\Editable\Area\Info;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class About
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-25)
 * @since 0.1.0 (2023-09-25) First version.
 */
class About extends AbstractAreabrick
{
    /**
     * @param LinkProjectRepository $projectLinkRepository
     * @param AboutPhilosophyDevelopmentRepository $aboutPhilosophyDevelopmentRepository
     * @param AboutPhilosophyReleasemanagementRepository $aboutPhilosophyReleasemanagementRepository
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    public function __construct(
        protected LinkProjectRepository $projectLinkRepository,
        protected AboutPhilosophyDevelopmentRepository $aboutPhilosophyDevelopmentRepository,
        protected AboutPhilosophyReleasemanagementRepository $aboutPhilosophyReleasemanagementRepository
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
        return 'About';
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
            'linkProjects' => $this->projectLinkRepository->all(),
            'aboutPhilosophyDevelopmentMain' => $this->aboutPhilosophyDevelopmentRepository->main(),
            'aboutPhilosophyDevelopmentNotMain' => $this->aboutPhilosophyDevelopmentRepository->notMain(),
            'aboutPhilosophyReleasemanagements' => $this->aboutPhilosophyReleasemanagementRepository->all(),
            'locale' => $request->getLocale(),
        ]);

        return null;
    }
}