<?php

/**
 * Class SvzplanPage
 *
 * Floor plan sketch of the "Sportvereinszentrum" (SVZ), a subpage of the SVZ page.
 * On the SVZ (sub)domain the sketch is available as short URL (vermietung.sf-bronnen.de/grundriss), see plugin sfb-svz.
 *
 * @author Magnus Buk <MagnusBuk@gmx.de>
 */
class SvzplanPage extends RoleAwarePage
{
    public function url($options = null): string
    {
        if ($options === null && SvzDomain::enabled() && $this->id() === SvzDomain::planPageId()) {
            return SvzDomain::url(SvzDomain::PLAN_SLUG);
        }

        return parent::url($options);
    }
}
