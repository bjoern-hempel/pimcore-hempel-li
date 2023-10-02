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

namespace App\EventSubscriber;

use App\Service\Entity\NewsService;
use Exception;
use Pimcore\Event\DataObjectEvents;
use Pimcore\Event\Model\DataObjectEvent;
use Pimcore\Model\DataObject\News;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class PimcoreAdminSubscriber
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-25)
 * @since 0.1.0 (2023-09-25) First version.
 */
readonly class PimcoreAdminSubscriber implements EventSubscriberInterface
{
    /**
     * @param NewsService $newsService
     */
    public function __construct(
        private NewsService $newsService
    )
    {
    }

    /**
     * Returns the described events.
     *
     * @return array<string, array<int, array<int, string|int>>>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            DataObjectEvents::PRE_UPDATE => [
                ['setNewsSeo', 10]
            ]
        ];
    }

    /**
     * Trigger the seo functionality.
     *
     * @param DataObjectEvent $event
     * @return void
     * @throws Exception
     */
    public function setNewsSeo(DataObjectEvent $event): void
    {
        $object = $event->getObject();

        if (!$object instanceof News) {
            return;
        }

        $this->newsService->setSeo($object);
    }
}