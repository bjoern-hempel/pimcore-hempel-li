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

use App\DataMapper\News\NewsLatestDataMapper;
use App\Document\Areabrick\Base\AbstractAreabrick;
use App\Repository\LinkSocialRepository;
use LogicException;
use Pimcore\Model\Document\Editable\Area\Info;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Hero
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-25)
 * @since 0.1.0 (2023-09-25) First version.
 */
class Hero extends AbstractAreabrick
{
    /**
     * @param LinkSocialRepository $socialLinkRepository
     */
    public function __construct(protected LinkSocialRepository $socialLinkRepository)
    {
    }

    /**
     * Returns the name of the area brick.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'Hero';
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
            'linkSocials' => $this->socialLinkRepository->all(),
            'locale' => $request->getLocale(),
            'latestBlogs' => NewsLatestDataMapper::getLast($request),
        ]);

        return null;
    }

    /**
     * @return bool
     */
    public function needsReload(): bool
    {
        return true;
    }
}