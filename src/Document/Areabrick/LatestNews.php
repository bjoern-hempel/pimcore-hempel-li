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
use Pimcore\Model\DataObject\News;
use Pimcore\Model\Document\Editable\Area\Info;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class LatestNews
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-25)
 * @since 0.1.0 (2023-09-25) First version.
 */
class LatestNews extends AbstractAreabrick
{
    /**
     * Returns the name of the area brick.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'Latest News';
    }

    /**
     * Adds some listing order keys, the order and the limit.
     *
     * @param Info $info
     * @return Response|null
     */
    public function action(Info $info): ?Response
    {
        $news = new News\Listing();
        $news->setOrderKey('date');
        $news->setOrder('desc');
        $news->setLimit(3);

        $info->setParams([
            'news' => NewsLatestDataMapper::listDataMapperFactory($news->load())->getArray(new Request()),
        ]);

        return null;
    }
}