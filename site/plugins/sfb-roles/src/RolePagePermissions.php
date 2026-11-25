<?php

use Kirby\Guards\PagePermissions;

/**
 * Class RolePagePermissions
 *
 * Permissions of a page which respect the page tree rules of the user role (see RolePageAccess):
 *
 *  - a page which is hidden for the role of the user can neither be accessed, listed nor read
 *  - on a page which is restricted for the role (level "readonly", also "hidden") only the reading actions are
 *    allowed, every other action is denied. So the panel shows the page without editable form and actions
 *    and the server rejects every change.
 *
 * The reading actions are a whitelist, so new actions of Kirby are denied on restricted pages by default.
 * Everything else is up to the standard permissions of Kirby (role and blueprint).
 *
 * @author Magnus Buk <MagnusBuk@gmx.de>
 */
class RolePagePermissions extends PagePermissions
{
    /**
     * Actions which only read a page, they are allowed on a read only page
     */
    const READ = [
        'access',
        'list',
        'preview',
        'read',
    ];

    /**
     * Actions which are denied on a hidden page
     */
    const HIDDEN = [
        'access',
        'list',
        'read',
    ];

    public function ensure(string $action, bool $default = false): void
    {
        $level = $this->level();

        if ($level === RolePageAccess::HIDDEN && in_array($action, self::HIDDEN, true)) {
            $this->error($action);
        }

        if ($level !== null && !in_array($action, self::READ, true)) {
            $this->error($action);
        }

        parent::ensure($action, $default);
    }

    /**
     * Level the page is restricted to for the user of these permissions (null if it is not restricted)
     */
    public function level(): ?string
    {
        if ($this->user->isKirby()) {
            return null;
        }

        return RolePageAccess::levelFor($this->model, $this->user->role()->id());
    }
}
