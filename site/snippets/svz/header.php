<?php
/**
 * Header of the SVZ site on its own domain (replaces the top bar, header and main navigation of the club site).
 * All links which leave the SVZ site lead to the main domain, see plugin sfb-svz.
 */
$mainUrl = SvzDomain::mainUrl();
$contactUrl = SvzDomain::contactUrl();
?>
<div class="svz-sitebar">
    <div class="container svz-sitebar__inner">
        <p class="svz-sitebar__note">Ein Angebot der Sportfreunde Bronnen e.V.</p>
        <a href="<?= $mainUrl;?>" class="svz-sitebar__back" title="Zur Vereinsseite der Sportfreunde Bronnen"><i class="fa fa-angle-left" aria-hidden="true"></i> Zur Vereinsseite <span>sf-bronnen.de</span></a>
    </div>
</div>
<header class="svz-siteheader">
    <div class="container svz-siteheader__inner">
        <a href="/" class="svz-brand" title="Sportvereinszentrum Bronnen - Startseite">
            <span class="svz-brand__logo"><img src="/assets/images/sfb/logo.svg" alt="Wappen der Sportfreunde Bronnen"></span>
            <span class="svz-brand__text">
                <span class="svz-brand__name">Sportvereinszentrum</span>
                <span class="svz-brand__place">Bronnen bei Laupheim</span>
            </span>
        </a>
        <a href="<?= $contactUrl;?>" class="svz-btn svz-btn--small svz-btn--accent svz-siteheader__cta"><i class="fa fa-envelope-o" aria-hidden="true"></i> Anfrage senden</a>
    </div>
</header>
