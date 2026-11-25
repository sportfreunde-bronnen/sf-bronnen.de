<?php

use Kirby\Cms\Page;
use Kirby\Cms\Site;
use Kirby\Cms\User;
use Kirby\Exception\PermissionException;
use Kirby\Guards\PageGuards;

/**
 * Class RolePageGuards
 *
 * Guards of a page which apply the page tree rules of the user role (see RolePageAccess):
 *
 *  - the permissions are replaced by RolePagePermissions
 *  - a page must not be moved below a page which is restricted for the role of the user
 *
 * @author Magnus Buk <MagnusBuk@gmx.de>
 */
class RolePageGuards extends PageGuards
{
    public function __construct(Page $model, User $user)
    {
        parent::__construct($model, $user);

        $this->permissions = new RolePagePermissions($model, $user);
    }

    /**
     * Denies moving a page below a page which is restricted (read only or hidden) for the role of the user
     *
     * @throws PermissionException
     */
    protected function ensureToMove(Site|Page $parent): void
    {
        if ($parent instanceof Page && $this->isRestricted($parent)) {
            throw new PermissionException(
                message: 'Die Seite "' . $this->model->title() . '" kann nicht unter "' . $parent->title() . '" verschoben werden, dieser Bereich ist für Ihre Rolle gesperrt.'
            );
        }

        parent::ensureToMove($parent);
    }

    private function isRestricted(Page $page): bool
    {
        if ($this->user->isKirby()) {
            return false;
        }

        return RolePageAccess::levelFor($page, $this->user->role()->id()) !== null;
    }
}
