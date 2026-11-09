<?php

use Kirby\Cms\File;
use Kirby\Cms\Page;
use Kirby\Cms\Site;
use Kirby\Exception\PermissionException;

/**
 * Class RolePageAccess
 *
 * Resolves which pages (including all their subpages) are restricted for a user role in the panel.
 * The rules are maintained by the admins in the field "rolePermissions" of the site (tab "Seitenrechte"):
 * every rule consists of a role, a level and a selection of pages.
 *
 *  - HIDDEN:   the page is invisible in the panel and cannot be opened
 *  - READONLY: the page is visible, but cannot be changed
 *
 * If a page is affected by several rules the stricter level wins. The admin and visitors who are not logged in
 * (frontend) are never restricted.
 *
 * @author Magnus Buk <MagnusBuk@gmx.de>
 */
final class RolePageAccess
{
    /**
     * The page is invisible in the panel
     */
    const HIDDEN = 'hidden';

    /**
     * The page is visible in the panel, but cannot be changed
     */
    const READONLY = 'readonly';

    /**
     * Valid levels
     */
    const LEVELS = [self::HIDDEN, self::READONLY];

    /**
     * Roles which are never restricted
     */
    const UNRESTRICTED_ROLES = ['admin', 'nobody'];

    /**
     * Name of the site field which contains the rules
     */
    const FIELD = 'rolePermissions';

    /**
     * Rules per role which have already been resolved in this request: [roleId => [pageId => level]]
     *
     * @var array[]
     */
    private static array $rules = [];

    /**
     * Roles whose rules are just being resolved (the page lookup must not run into the rules again)
     *
     * @var bool[]
     */
    private static array $resolving = [];

    /**
     * Returns the level a page is restricted to for a role, null if the page is not restricted.
     * The restriction applies to the page itself and to all its subpages (even those not created yet).
     *
     * @param Page|string $page   Page or page id
     * @param string|null $roleId Role id, null for the role of the current user (null for visitors)
     */
    public static function levelFor(Page|string $page, ?string $roleId = null): ?string
    {
        $roleId ??= self::currentRoleId();
        if ($roleId === null || in_array($roleId, self::UNRESTRICTED_ROLES, true)) {
            return null;
        }

        $rules = self::rules($roleId);
        if ($rules === []) {
            return null;
        }

        $id = is_string($page) ? trim($page, '/') : $page->id();
        $level = null;
        foreach ($rules as $ruleId => $ruleLevel) {
            if ($id !== $ruleId && !str_starts_with($id, $ruleId . '/')) {
                continue;
            }
            if ($ruleLevel === self::HIDDEN) {
                return self::HIDDEN;
            }
            $level = self::READONLY;
        }

        return $level;
    }

    /**
     * Denies changes of a file if its page is restricted (read only or hidden) for the role of the current user.
     * Kirby has no file models, so the file hooks of the plugin call this method.
     *
     * @throws PermissionException
     */
    public static function guardFile(File $file): void
    {
        $parent = $file->parent();
        if ($parent instanceof Page && self::levelFor($parent) !== null) {
            throw new PermissionException(
                message: 'Die Seite "' . $parent->title() . '" ist für Ihre Rolle schreibgeschützt, ihre Dateien können nicht geändert werden.'
            );
        }
    }

    /**
     * Denies moving a page below a page which is restricted (read only or hidden) for the role of the current user
     *
     * @throws PermissionException
     */
    public static function guardMove(Page $page, Page|Site $parent): void
    {
        if ($parent instanceof Page && self::levelFor($parent) !== null) {
            throw new PermissionException(
                message: 'Die Seite "' . $page->title() . '" kann nicht unter "' . $parent->title() . '" verschoben werden, dieser Bereich ist für Ihre Rolle gesperrt.'
            );
        }
    }

    /**
     * Returns the rules of a role as [pageId => level]
     * (resolved once per request, rules of deleted pages are ignored)
     */
    public static function rules(string $roleId): array
    {
        if (isset(self::$rules[$roleId])) {
            return self::$rules[$roleId];
        }
        if (isset(self::$resolving[$roleId])) {
            return [];
        }

        self::$resolving[$roleId] = true;
        try {
            $rules = self::resolve($roleId);
        } finally {
            unset(self::$resolving[$roleId]);
        }

        return self::$rules[$roleId] = $rules;
    }

    /**
     * Forgets all resolved rules (they are resolved again on the next request for a rule)
     */
    public static function reset(): void
    {
        self::$rules = [];
        self::$resolving = [];
    }

    /**
     * Reads the rules of a role from the site content
     */
    private static function resolve(string $roleId): array
    {
        $rules = [];
        $structure = kirby()->site()->content()->get(self::FIELD)->toStructure();

        foreach ($structure as $entry) {
            if (trim((string)$entry->content()->get('role')->value()) !== $roleId) {
                continue;
            }
            $level = trim((string)$entry->content()->get('level')->value());
            if (!in_array($level, self::LEVELS, true)) {
                continue;
            }

            foreach ($entry->content()->get('pages')->toPages() as $page) {
                // the stricter level wins
                if (($rules[$page->id()] ?? null) === self::HIDDEN) {
                    continue;
                }
                $rules[$page->id()] = $level;
            }
        }

        return $rules;
    }

    /**
     * Id of the role of the current user (null if nobody is logged in)
     */
    private static function currentRoleId(): ?string
    {
        return kirby()->user()?->role()->id();
    }
}
