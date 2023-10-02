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

namespace App\Service\QueryBuilder;

use App\Constant\KeyDb;
use App\Service\QueryBuilder\Base\AbstractListingQueryBuilder;
use InvalidArgumentException;
use Pimcore\Model\DataObject\News;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class NewsListingQueryBuilder
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-25)
 * @since 0.1.0 (2023-09-25) First version.
 */
class NewsListingQueryBuilder extends AbstractListingQueryBuilder
{
    /**
     * @param News\Listing $listing
     * @param Request $request
     */
    public function __construct(
        News\Listing $listing,
        Request $request
    )
    {
        parent::__construct($listing, $request);
    }

    /**
     * Do some logic before the query is built.
     *
     * @return void
     */
    protected function addQueryBefore(): void
    {
        $this->queryBuilder->distinct();

        $this->queryBuilder->leftJoin(
            'object_news',
            'object_relations_news',
            'NewsRelations',
            'object_news.oo_id = NewsRelations.src_id'
        );

        $this->queryBuilder->orderBy('object_news.date', KeyDb::DESC);
    }

    /**
     * Add tag filters.
     *
     * @param string $term
     * @return void
     */
    public function query(string $term): void
    {
        $term = trim($term);

        if (empty($term)) {
            return;
        }

        $this->queryBuilder->innerJoin(
            'NewsRelations',
            'object_news_tag',
            'NewsTag',
            'NewsRelations.dest_id = NewsTag.oo_id AND '.
            'NewsRelations.type = "object" AND '.
            'NewsTag.className = "NewsTag"'
        );

        $termLike = $this->getLikeTerm($term);

        $this->queryBuilder
            ->andWhere(
                $this->queryBuilder->expr()->or(
                    $this->queryBuilder->expr()->like('object_news.title', $termLike),
                    $this->queryBuilder->expr()->like('NewsTag.key', $termLike)
                )
            );
    }

    /**
     * Add category filters.
     *
     * @param array<int, int> $categories
     * @return void
     */
    public function categories(array $categories = []): void
    {
        if (count($categories) <= 0) {
            return;
        }

        foreach ($categories as $category) {
            $termLike = $this->getLikeTerm(sprintf(',%s,', $category));

            $this->queryBuilder->andWhere(
                $this->queryBuilder->expr()->like("CONCAT(',', object_news.categories, ',')", $termLike),
            );
        }
    }

    /**
     * Add order by functions (DESC or ASC).
     *
     * @param string $order
     * @return void
     */
    public function sortByDate(string $order = KeyDb::DESC): void
    {
        if (!in_array($order, [KeyDb::ASC, KeyDb::DESC], true)) {
            throw new InvalidArgumentException(sprintf('Invalid order "%s".', $order));
        }

        $this->queryBuilder->orderBy('object_news.date', $order);
    }
}
