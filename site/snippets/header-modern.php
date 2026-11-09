<?php
/**
 * Modern alternative of the header and the main navigation (selected with the option 'sfb.header' => 'modern',
 * see snippet header): graphite info bar with the social links and the department, below it the sticky main bar
 * with the club crest (or the crest of the darts department), the name and the menu.
 *
 * Parameters:
 * - $isDarts: true on the pages of the darts department (crest and name of the department are shown then)
 *
 * No <nav> element on purpose: custom.js pins every <nav> to the top of the window while scrolling,
 * the header is sticky on its own (CSS).
 */
$isDarts = $isDarts ?? false;
?>
<?php if (EXTRACT_CONTENT === false): ?>
<div class="mh-topbar d-none d-md-block">
    <div class="container ps-sm-0 pe-sm-0">
        <div class="mh-topbar-inner">
            <ul class="mh-social list-unstyled mb-0">
                <?php snippet('social-links', ['itemClass' => 'mh-social-item']); ?>
            </ul>
            <p class="mh-department mb-0"><span class="mh-department-dot" aria-hidden="true"></span><?php snippet('department'); ?></p>
        </div>
    </div>
</div>
<header class="mh-header" id="siteHeader">
    <div class="mh-nav navbar navbar-expand-lg" role="navigation" aria-label="Hauptmenü">
        <div class="container ps-sm-0 pe-sm-0 mh-container">
            <a href="/" class="mh-brand<?= $isDarts ? ' mh-brand--darts' : '';?>" title="Zur Startseite">
                <?php if ($isDarts): ?>
                    <img class="mh-logo rotate" src="/assets/images/sfb/sfbdarts.svg" alt="Logo der Dartsabteilung der Sportfreunde Bronnen">
                <?php else: ?>
                    <img class="mh-logo rotate" src="/assets/images/sfb/logo.svg" alt="Logo der Sportfreunde Bronnen">
                <?php endif; ?>
                <span class="mh-claim">
                    <span class="mh-claim-name">Sportfreunde Bronnen e.V.</span>
                </span>
            </a>
            <button class="navbar-toggler mh-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#mhMenu" aria-controls="mhMenu" aria-expanded="false" aria-label="Menü öffnen oder schließen">
                <span class="mh-toggler-bars" aria-hidden="true"><span></span><span></span><span></span></span>
            </button>
            <div class="collapse navbar-collapse mh-collapse" id="mhMenu">
                <ul class="navbar-nav mh-menu">
                    <?php snippet('navigation-modern'); ?>
                </ul>
                <div class="mh-panel-social d-md-none">
                    <p class="mh-panel-social-title">Folge uns</p>
                    <ul class="mh-social list-unstyled mb-0">
                        <?php snippet('social-links', ['itemClass' => 'mh-social-item']); ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>
<?php endif; ?>
