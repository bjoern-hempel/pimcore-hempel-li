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

use App\Service\QueryBuilder\NewsListingQueryBuilder;
use InvalidArgumentException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Pimcore\Model\DataObject\News;
use Pimcore\Model\DataObject\Concrete;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class NewsRepository
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-25)
 * @since 0.1.0 (2023-09-25) First version.
 */
class NewsRepository
{
    /**
     * Finds the given news with its given identifier.
     *
     * @param int|string $id
     * @return News|null
     */
    public function find(int|string $id): News|null
    {
        return News::getById($id);
    }

    /**
     * Returns a paginated list of news.
     *
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return PaginationInterface<int, Concrete>
     */
    public function paginate(Request $request, PaginatorInterface $paginator): PaginationInterface
    {
        $perPage = $request->get('perPage', 50);
        $page = $request->get('page', 1);

        if (!is_string($perPage) && !is_int($perPage)) {
            throw new InvalidArgumentException('Invalid perPage value');
        }

        if (!is_string($page) && !is_int($page)) {
            throw new InvalidArgumentException('Invalid perPage value');
        }

        $news = (new NewsListingQueryBuilder(new News\Listing(), $request))->getListing();

        return $paginator->paginate($news, (int) $page, (int) $perPage);
    }
}
