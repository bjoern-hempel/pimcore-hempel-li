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

use App\Constant\Parameter;
use Pimcore\Controller\FrontendController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HomeController
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-25)
 * @since 0.1.0 (2023-09-25) First version.
 */
class HomeController extends FrontendController
{
    /**
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(private readonly ParameterBagInterface $parameterBag)
    {
    }

    /**
     * Renders the / page.
     *
     * @param Request $request
     * @return Response
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function indexAction(Request $request): Response
    {
        return $this->render('home/index.html.twig', [
            'locale' => $request->getLocale(),
            'googleApiKey' => $this->parameterBag->get(Parameter::ENVIRONMENT_GOOGLE_API_KEY),
        ]);
    }
}
