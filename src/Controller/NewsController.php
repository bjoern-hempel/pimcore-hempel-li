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

namespace App\Controller;

use App\DataMapper\News\NewsCategoryDataMapper;
use App\DataMapper\News\NewsListingDataMapper;
use App\DataMapper\News\NewsShowDataMapper;
use App\DataMapper\Pagination\PaginatorDataMapper;
use App\Repository\NewsCategoryRepository;
use App\Repository\NewsRepository;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class NewsController
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-25)
 * @since 0.1.0 (2023-09-25) First version.
 */
class NewsController extends FrontendController
{
    /**
     * @param NewsRepository $newsRepository
     * @param NewsCategoryRepository $newsCategoryRepository
     */
    public function __construct(
        private readonly NewsRepository         $newsRepository,
        private readonly NewsCategoryRepository $newsCategoryRepository,
    )
    {
    }

    /**
     * Renders the /blog page.
     *
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function indexAction(Request $request, PaginatorInterface $paginator): Response
    {
        $paginator = $this->newsRepository->paginate($request, $paginator);
        $newsCategories = $this->newsCategoryRepository->all();

        return $this->render('news/index.html.twig', [
            'locale' => $request->getLocale(),
            'paginator' => (new PaginatorDataMapper($paginator))->getArray($request),
            'news' => NewsListingDataMapper::listDataMapperFactory($paginator->getItems())->getArray($request),
            'news_categories' => NewsCategoryDataMapper::listDataMapperFactory($newsCategories)->getArray($request),
        ]);
    }

    /**
     * Renders the /blog/{slug} page.
     *
     * @param Request $request
     * @param int $newsId
     * @return Response
     * @throws Exception
     */
    #[Route('/en/blog/{slug}-{newsId}', name: 'news_show', requirements: ['slug' => '[\w-]+', 'newsId' => '\d+'])]
    public function showAction(Request $request, int $newsId): Response
    {
        $news = $this->newsRepository->find($newsId);

        if (empty($news)) {
            throw new Exception('No news found for id '.$newsId);
        }

        return $this->render('news/show.html.twig', [
            'locale' => $request->getLocale(),
            'news_item' => (new NewsShowDataMapper($news))->getArray($request),
        ]);
    }
}
