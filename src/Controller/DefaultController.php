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

use App\Repository\LinkSocialRepository;
use LogicException;
use Pimcore\Bundle\AdminBundle\Controller\Admin\LoginController;
use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-25)
 * @since 0.1.0 (2023-09-25) First version.
 */
class DefaultController extends FrontendController
{
    /**
     * @param LinkSocialRepository|null $linkSocialRepository
     */
    public function __construct(protected LinkSocialRepository|null $linkSocialRepository = null)
    {
    }

    /**
     * Renders the default page.
     *
     * @param Request $request
     * @return Response
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function defaultAction(Request $request): Response
    {
        return $this->render('default/default.html.twig');
    }

    /**
     * Renders the footer page.
     *
     * @param Request $request
     * @return Response
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function footerAction(Request $request): Response
    {
        if (is_null($this->linkSocialRepository)) {
            throw new LogicException('No LinkSocialRepository class given');
        }

        return $this->render('include/footer.html.twig', [
            'linkSocials' => $this->linkSocialRepository->all(),
        ]);
    }

    /**
     * Renders the error or 404 page.
     *
     * @param Request $request
     * @return array<string, string>
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function errorAction(Request $request): array
    {
        return [];
    }
    
    /**
     * Forwards the request to admin login
     */
    public function loginAction(): Response
    {
        return $this->forward(LoginController::class.'::loginCheckAction');
    }
}
