<?php
/**
 * Landing page of the Sportvereinszentrum (SVZ).
 *
 * The content is split into tabs (see $tabs). Every tab has its own panel snippet below site/snippets/svz/.
 * To add a further section (e.g. a price calculator) add an entry to $tabs and render its panel below.
 *
 * @var SvzPage $page
 */

$hero = $page->image('p2223766.jpg') ?? $page->gallery()->toFiles()->first();

$tabs = [
    'infos' => ['label' => 'Infos', 'icon' => 'fa-info-circle'],
    'verfuegbarkeit' => ['label' => 'Verfügbarkeit', 'icon' => 'fa-calendar-check-o'],
    'preise' => ['label' => 'Preise', 'icon' => 'fa-eur'],
    'faqs' => ['label' => 'FAQs', 'icon' => 'fa-question-circle'],
];

// the FAQ tab is only shown if questions are maintained in the backend
$hasFaqs = $page->faqs()->isNotEmpty();

if (!$hasFaqs) {
    unset($tabs['faqs']);
}

// contact form of the SVZ (subpage 'anfrage'), the club's contact form is only the fallback
$contactUrl = SvzDomain::contactUrl();
$calendarUrl = SvzDomain::link('svz/termin');
// links which leave the SVZ site lead to the main domain if the SVZ site is served on its own domain
$downloadsUrl = SvzDomain::link('gesamtverein/allgemeines/downloads');
?>
<?php snippet('header', ['styles' => ['svz']]); ?>
<div class="page page--svz">
<div class="svz" data-svz data-tariff="regular">
    <script>document.documentElement.classList.add('svz-js');</script>

    <section class="svz-hero">
        <?php if ($hero): ?>
            <div class="svz-hero__media" aria-hidden="true">
                <img src="<?= $hero->resize(1600)->url();?>"
                     srcset="<?= $hero->srcset([800, 1200, 1600, 2000]);?>"
                     sizes="100vw" alt="" fetchpriority="high">
            </div>
        <?php endif; ?>
        <div class="container svz-hero__inner">
            <p class="svz-hero__eyebrow">Sportfreunde Bronnen e.V.</p>
            <h1 class="svz-hero__title"><?= $page->title() . APPEND_TITLE;?></h1>
            <p class="svz-hero__lead"><?= $page->headline()->esc();?></p>
            <div class="svz-actions">
                <a href="#verfuegbarkeit" data-svz-tab="verfuegbarkeit" class="svz-btn svz-btn--accent"><i class="fa fa-calendar-check-o" aria-hidden="true"></i> Verfügbarkeit prüfen</a>
                <a href="#preise" data-svz-tab="preise" class="svz-btn svz-btn--ghost"><i class="fa fa-eur" aria-hidden="true"></i> Preise ansehen</a>
                <a href="<?= $contactUrl;?>" class="svz-btn svz-btn--link">Direkt anfragen <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
            </div>
            <?php if ($page->highlights()->isNotEmpty()): ?>
                <ul class="svz-stats">
                    <?php foreach ($page->highlights()->toStructure()->limit(4) as $highlight): ?>
                        <li>
                            <strong><?= $highlight->value()->esc();?></strong>
                            <span><?= $highlight->label()->esc();?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </section>

    <nav class="svz-tabs" data-svz-tabs aria-label="Bereiche des Sportvereinszentrums">
        <div class="container svz-tabs__inner">
            <ul class="svz-tabs__list">
                <?php foreach ($tabs as $id => $tab): ?>
                    <li>
                        <a href="#<?= $id;?>" data-svz-tab="<?= $id;?>"><i class="fa <?= $tab['icon'];?>" aria-hidden="true"></i> <span><?= $tab['label'];?></span></a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <a href="<?= $contactUrl;?>" class="svz-btn svz-btn--small svz-btn--primary svz-tabs__cta">Jetzt anfragen</a>
        </div>
    </nav>

    <div class="svz-panels" data-svz-panels>
        <div class="container">
            <section class="svz-panel" id="infos" data-svz-panel="infos">
                <?php snippet('svz/info', ['page' => $page]); ?>
            </section>

            <section class="svz-panel" id="verfuegbarkeit" data-svz-panel="verfuegbarkeit">
                <div class="svz-section-head svz-reveal">
                    <p class="svz-eyebrow">Verfügbarkeit</p>
                    <h2 class="svz-title">Ist das SVZ an Ihrem Wunschtermin frei?</h2>
                    <?php if ($page->availabilityText()->isNotEmpty()): ?>
                        <div class="svz-lead"><?= $page->availabilityText()->kirbytext();?></div>
                    <?php endif; ?>
                </div>
                <?php snippet('svz/calendar', ['bookings' => $page->bookings()]); ?>
            </section>

            <section class="svz-panel" id="preise" data-svz-panel="preise">
                <?php snippet('svz/prices', ['page' => $page]); ?>
            </section>

            <?php if ($hasFaqs): ?>
                <section class="svz-panel" id="faqs" data-svz-panel="faqs">
                    <?php snippet('svz/faq', ['page' => $page]); ?>
                </section>
            <?php endif; ?>
        </div>
    </div>
    <script>
        // select the start tab before the first paint (deep link via #preise, #verfuegbarkeit, ...)
        (function () {
            var id = window.location.hash.replace('#', '');
            var panels = [].slice.call(document.querySelectorAll('[data-svz-panel]'));
            var known = panels.some(function (panel) { return panel.getAttribute('data-svz-panel') === id; });
            var start = known ? id : panels[0].getAttribute('data-svz-panel');
            panels.forEach(function (panel) { panel.classList.toggle('is-active', panel.getAttribute('data-svz-panel') === start); });
            [].slice.call(document.querySelectorAll('[data-svz-tabs] [data-svz-tab]')).forEach(function (link) { link.classList.toggle('is-active', link.getAttribute('data-svz-tab') === start); });
        })();
    </script>

    <section class="svz-cta">
        <div class="container">
            <div class="svz-cta__head svz-reveal">
                <p class="svz-eyebrow svz-eyebrow--light">Ihre Anfrage</p>
                <h2 class="svz-title svz-title--light">In drei Schritten zu Ihrer Feier</h2>
            </div>
            <ol class="svz-steps">
                <li class="svz-reveal">
                    <span class="svz-steps__num">1</span>
                    <h3>Termin prüfen</h3>
                    <p>Im Kalender sehen Sie sofort, ob Ihr Wunschtermin frei ist.</p>
                </li>
                <li class="svz-reveal">
                    <span class="svz-steps__num">2</span>
                    <h3>Anfrage senden</h3>
                    <p>Schnell und einfach über unser Kontaktformular.</p>
                </li>
                <li class="svz-reveal">
                    <span class="svz-steps__num">3</span>
                    <h3>Feiern</h3>
                    <p>Wir freuen uns auf Ihre Veranstaltung im SVZ Bronnen.</p>
                </li>
            </ol>
            <div class="svz-actions svz-actions--center svz-reveal">
              <a href="<?= $contactUrl;?>" class="svz-btn svz-btn--accent svz-btn--large"><i class="fa fa-envelope-o" aria-hidden="true"></i> Jetzt Termin anfragen</a>
              <a href="<?= $downloadsUrl;?>" class="svz-btn svz-btn--ghost svz-btn--large"><i class="fa fa-download" aria-hidden="true"></i> Nutzungsvertrag &amp; Preisliste</a>
            </div>
        </div>
    </section>
</div>
</div>
<?php snippet('footer', ['scripts' => ['svz']]); ?><?php snippet('close'); ?>
