<?php

/**
 * Class SvzcontactPage
 *
 * Contact form of the "Sportvereinszentrum" (SVZ), a subpage of the SVZ page.
 * On the SVZ (sub)domain the form is available as short URL (svz.sf-bronnen.de/anfrage), see plugin sfb-svz.
 *
 * @author Magnus Buk <MagnusBuk@gmx.de>
 */
class SvzcontactPage extends Page
{
    public function url($options = null): string
    {
        if ($options === null && SvzDomain::enabled() && $this->id() === SvzDomain::contactPageId()) {
            return SvzDomain::url(SvzDomain::CONTACT_SLUG);
        }

        return parent::url($options);
    }
}
