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

use App\Constant\KeyDb;
use Pimcore\Model\DataObject\LinkSocial;

/**
 * Class LinkSocialRepository
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-26)
 * @since 0.1.0 (2023-09-26) First version.
 */
class LinkSocialRepository
{
    /**
     * Finds the given news with its given identifier.
     *
     * @return LinkSocial[]
     */
    public function all(): array
    {
        $linkSocials = new LinkSocial\Listing();
        $linkSocials->setCondition('path = ?', '/Link/Socials/');
        $linkSocials->setOrderKey('order');
        $linkSocials->setOrder(KeyDb::ASC);

        return $linkSocials->load();
    }
}
