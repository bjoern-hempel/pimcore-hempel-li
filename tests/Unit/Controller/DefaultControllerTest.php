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

namespace App\Tests\Unit\Controller;

use App\Controller\DefaultController;
use Codeception\Test\Unit;
use LogicException;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use Pimcore\Config;
use Pimcore\Templating\TwigDefaultDelegatingEngine;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;

/**
 * Class DefaultControllerTest
 *
 * This basic unit test demonstrates how to unit-test a controller using mocked twig engine.
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-25)
 * @since 0.1.0 (2023-09-25) First version.
 */
class DefaultControllerTest extends Unit
{
    private DefaultController $controller;

    private MockObject|Environment $twig;

    /**
     * @return void
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Create a test double for twig engine.
        // See: https://phpunit.readthedocs.io/en/9.5/test-doubles.html
        $this->twig = $this->createMock(Environment::class);

        $container = new Container();
        $container->set('twig', $this->twig);
        $container->set('pimcore.templating', new TwigDefaultDelegatingEngine($this->twig, new Config()));

        $this->controller = new DefaultController();
        $this->controller->setContainer($container);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testDefaultAction()
    {
        if (!method_exists($this->twig, 'method')) {
            throw new LogicException('The method "method" does not exist in the twig engine.');
        }

        $this->twig->method('render')->will(
            $this->returnValueMap([
                // Simulate rendering of default template.
                ['default/default.html.twig', [], 'At pimcore we love writing tests! ❤️TDD!'],
            ])
        );

        $response = $this->controller->defaultAction($this->createMock(Request::class));

        self::assertEquals(200, $response->getStatusCode());
        self::assertStringContainsStringIgnoringCase('pimcore', $response->getContent() ?: '');
        self::assertStringContainsStringIgnoringCase('❤', $response->getContent() ?: '');
        self::assertStringContainsStringIgnoringCase('tests', $response->getContent() ?: '');
        self::assertStringNotContainsStringIgnoringCase('bugs', $response->getContent() ?: '');
        self::assertStringNotContainsStringIgnoringCase('hacks', $response->getContent() ?: '');
        self::assertStringNotContainsStringIgnoringCase('💩', $response->getContent() ?: '');
    }
}
