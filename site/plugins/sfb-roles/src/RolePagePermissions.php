<?php

use Kirby\Cms\PagePermissions;

/**
 * Class RolePagePermissions
 *
 * Page permissions which respect the page tree rules of the user role (see RolePageAccess): on a page which is
 * restricted for the role of the current user (level "readonly", also "hidden") all write actions are denied,
 * so the panel shows the page without editable form and actions and the server rejects every change.
 *
 * The actions "access" and "list" are not touched here, Kirby caches them per role and template (see RoleAwarePage).
 *
 * @author Magnus Buk <MagnusBuk@gmx.de>
 */
class RolePagePermissions extends PagePermissions
{
    /**
     * Actions which change a page (or create/remove one below it)
     */
    const WRITE = [
        'changeSlug',
        'changeStatus',
        'changeTemplate',
        'changeTitle',
        'create',
        'delete',
        'duplicate',
        'move',
        'sort',
        'update',
    ];

    public function can(string $action, bool $default = false): bool
    {
        if (in_array($action, self::WRITE, true) && RolePageAccess::levelFor($this->model) !== null) {
            return false;
        }

        return parent::can($action, $default);
    }
}
