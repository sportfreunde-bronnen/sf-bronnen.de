<?php
/**
 * Course page of the department Gymnastik.
 *
 * Layout: hero card (headline, description, notice, photos), fact cards (trainer, place, times, costs,
 * registration with call-to-action buttons) and a random quote of the department.
 * Styles: frontend/src/sass/kurs.scss (loaded in addition to sfb.css).
 */

use Kirby\Toolkit\Str;

/** Splits a multi line field into its non-empty, trimmed lines. */
$lines = static fn ($field): array => array_values(array_filter(
    array_map('trim', preg_split('/\R/', (string)$field->value()) ?: []),
    static fn (string $line): bool => $line !== ''
));

$department = $page->parents()->last();
$images = $page->images()->limit(2);
$imageCount = $images->count();
$hasCosts = $page->kosten()->isNotEmpty();

// Registration: offer direct buttons if the field contains an e-mail address and/or a phone number
$registration = (string)$page->anmeldung()->value();
$email = preg_match('/[A-Za-z0-9._%+-]+@[A-Za-z0-9-]+(?:\.[A-Za-z0-9-]+)+/', $registration, $match) ? $match[0] : null;
$phone = preg_match('/\+?\d[\d\s\/-]{4,}\d/', $email ? str_replace($email, '', $registration) : $registration, $match)
    ? trim($match[0])
    : null;
$mailSubject = rawurlencode('Anfrage: ' . $page->title());

$quote = $department?->zitate()->toStructure()->shuffle()->first();
?>
<?php snippet('header', ['styles' => ['kurs']]); ?>
<?php snippet('h1'); ?>
<div class="page page--kurs">
    <div class="main-container container pl-sm-0 pr-sm-0">
        <article class="kurs">

            <section class="kurs-hero<?= $imageCount > 0 ? ' kurs-hero--media' : ''; ?>">
                <div class="kurs-hero__body">
                    <p class="kurs-hero__eyebrow">
                        <i class="fa fa-heartbeat" aria-hidden="true"></i>
                        <?= $department?->title()->esc(); ?><?php if ($page->parent()): ?> <span aria-hidden="true">·</span> <?= $page->parent()->title()->esc(); ?><?php endif; ?>
                    </p>
                    <h2 class="kurs-hero__title"><?= $page->headline()->esc(); ?></h2>
                    <div class="kurs-hero__text"><?= $page->text()->kirbytext(); ?></div>

                    <?php if ($page->hinweis()->isNotEmpty()): ?>
                        <aside class="kurs-notice" role="note">
                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                            <p><?= $page->hinweis()->kirbytextinline(); ?></p>
                        </aside>
                    <?php endif; ?>
                </div>

                <?php if ($imageCount > 0): ?>
                    <div class="kurs-hero__media<?= $imageCount > 1 ? ' kurs-hero__media--duo' : ''; ?>">
                        <?php foreach ($images->values() as $index => $image): ?>
                            <figure class="kurs-photo kurs-photo--<?= $index + 1; ?>">
                                <img src="<?= $image->resize(900)->url(); ?>"
                                     srcset="<?= $image->srcset([480, 720, 900]); ?>"
                                     sizes="(min-width: 992px) 420px, (min-width: 576px) 45vw, 90vw"
                                     alt="<?= $image->alt()->or($page->title() . ' bei den Sportfreunden Bronnen')->esc(); ?>"
                                     loading="lazy">
                            </figure>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>

            <section class="kurs-facts<?= $hasCosts ? ' kurs-facts--has-costs' : ''; ?>" aria-label="Kursinformationen">
                <article class="kurs-fact">
                    <span class="kurs-fact__icon"><i class="fa fa-user" aria-hidden="true"></i></span>
                    <h3 class="kurs-fact__label">Übungsleiter:in</h3>
                    <div class="kurs-fact__value"><?= $page->trainer()->kirbytext(); ?></div>
                </article>

                <article class="kurs-fact">
                    <span class="kurs-fact__icon"><i class="fa fa-map-marker" aria-hidden="true"></i></span>
                    <h3 class="kurs-fact__label">Trainingsort</h3>
                    <div class="kurs-fact__value"><?= $page->ort()->kirbytext(); ?></div>
                </article>

                <article class="kurs-fact">
                    <span class="kurs-fact__icon"><i class="fa fa-clock-o" aria-hidden="true"></i></span>
                    <h3 class="kurs-fact__label">Trainingszeiten</h3>
                    <ul class="kurs-fact__value kurs-times">
                        <?php foreach ($lines($page->zeiten()) as $line): ?>
                            <li><?= kti($line); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </article>

                <?php if ($hasCosts): ?>
                    <article class="kurs-fact kurs-fact--costs">
                        <span class="kurs-fact__icon"><i class="fa fa-eur" aria-hidden="true"></i></span>
                        <h3 class="kurs-fact__label">Kosten</h3>
                        <ul class="kurs-fact__value kurs-prices">
                            <?php foreach ($lines($page->kosten()) as $line): ?>
                                <?php if (preg_match('/^((?:[^:()]|\([^()]*\)){2,50}):\s*(.+)$/u', $line, $price)): ?>
                                    <li class="kurs-prices__row">
                                        <span class="kurs-prices__key"><?= esc($price[1]); ?></span>
                                        <span class="kurs-prices__value"><?= kti($price[2]); ?></span>
                                    </li>
                                <?php else: ?>
                                    <li class="kurs-prices__row kurs-prices__row--text"><?= kti($line); ?></li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </article>
                <?php endif; ?>

                <article class="kurs-fact kurs-fact--cta">
                    <span class="kurs-fact__icon"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>
                    <h3 class="kurs-fact__label">Anmeldung / Infos</h3>
                    <div class="kurs-fact__value"><?= $page->anmeldung()->kirbytext(); ?></div>
                    <?php if ($email || $phone): ?>
                        <div class="kurs-actions">
                            <?php if ($email): ?>
                                <a class="kurs-btn" href="mailto:<?= Str::encode($email); ?>?subject=<?= $mailSubject; ?>">
                                    <i class="fa fa-envelope" aria-hidden="true"></i> E-Mail schreiben
                                </a>
                            <?php endif; ?>
                            <?php if ($phone): ?>
                                <a class="kurs-btn<?= $email ? ' kurs-btn--ghost' : ''; ?>" href="tel:<?= preg_replace('/[^\d+]/', '', $phone); ?>">
                                    <i class="fa fa-phone" aria-hidden="true"></i> Anrufen
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </article>
            </section>

            <?php if ($quote): ?>
                <figure class="kurs-quote">
                    <blockquote class="kurs-quote__text"><p><?= $quote->text()->esc(); ?></p></blockquote>
                    <figcaption class="kurs-quote__author"><?= $quote->person()->esc(); ?></figcaption>
                </figure>
            <?php endif; ?>

        </article>
    </div>
</div>
<?php snippet('footer'); ?><?php snippet('close'); ?>
