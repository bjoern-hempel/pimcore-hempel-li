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

use Pimcore\Bundle\AdminBundle\PimcoreAdminBundle;
use Pimcore\Bundle\InstallBundle\Event\BundleSetupEvent;
use Pimcore\Bundle\InstallBundle\Event\InstallEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class BundleSetupSubscriber
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-12)
 * @since 0.1.0 (2023-09-12) First version.
 */
class BundleSetupSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            InstallEvents::EVENT_BUNDLE_SETUP => [
                ['bundleSetup'],
            ],
        ];
    }

    public function bundleSetup(BundleSetupEvent $event): void
    {
        // add required PimcoreAdminBundle
        $event->addRequiredBundle('PimcoreAdminBundle', PimcoreAdminBundle::class, true);
    }
}
