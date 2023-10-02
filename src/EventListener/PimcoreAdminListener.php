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

namespace App\EventListener;

use Pimcore\Event\BundleManager\PathsEvent;

/**
 * Class PimcoreAdminListener
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-29)
 * @since 0.1.0 (2023-09-29) First version.
 */
class PimcoreAdminListener
{
    /**
     * @param PathsEvent $event
     * @return void
     */
    public function addJSFiles(PathsEvent $event): void
    {
        $event->setPaths(
            array_merge(
                $event->getPaths(),
                [
                    '/admin/objects/data/markdown.js',
                    '/admin/objects/tags/markdown.js',
                ]
            )
        );
    }
}