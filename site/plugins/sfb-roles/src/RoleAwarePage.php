<?php

use Kirby\Cms\Page;
use Kirby\Cms\PagePermissions;

/**
 * Class RoleAwarePage
 *
 * Base class of all page models. Kirby decides on access rights per user role and page template only, so the rules
 * of RolePageAccess (page tree of a role) are applied here: a page which is hidden for the role of the current user
 * is neither accessible nor listable in the panel (tree, lists, search, page picker, direct call of the panel URL).
 * A page which is read only for the role cannot be changed (RolePagePermissions).
 *
 * Registered as default model in the plugin sfb-roles, all other page models extend this class.
 *
 * @author Magnus Buk <MagnusBuk@gmx.de>
 */
class RoleAwarePage extends Page
{
    public function isAccessible(): bool
    {
        if (RolePageAccess::levelFor($this) === RolePageAccess::HIDDEN) {
            return false;
        }

        return parent::isAccessible();
    }

    public function isListable(): bool
    {
        if (RolePageAccess::levelFor($this) === RolePageAccess::HIDDEN) {
            return false;
        }

        return parent::isListable();
    }

    public function permissions(): PagePermissions
    {
        return new RolePagePermissions($this);
    }
}
