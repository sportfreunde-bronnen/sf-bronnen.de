<?php
/**
 * "Infos" section of the SVZ page.
 *
 * @var SvzPage $page
 */

$images = $page->gallery()->toFiles();
$imageCount = $images->count();
$exterior = $page->image('svz_footer.jpg');
?>

<div class="svz-section-head svz-reveal">
    <p class="svz-eyebrow">Das bieten wir Ihnen</p>
    <h2 class="svz-title">Ihr Platz zum Feiern. So flexibel wie Sie es brauchen.</h2>
    <div class="svz-lead mt-2"><?= $page->text()->kirbytext();?></div>
    <div class="svz-actions">
        <a href="#verfuegbarkeit" data-svz-tab="verfuegbarkeit" class="svz-btn svz-btn--primary"><i class="fa fa-calendar-check-o" aria-hidden="true"></i> Verfügbarkeit prüfen</a>
        <a href="#preise" data-svz-tab="preise" class="svz-btn svz-btn--outline"><i class="fa fa-eur" aria-hidden="true"></i> Preise ansehen</a>
    </div>
</div>

<?php if ($page->features()->isNotEmpty()): ?>
    <ul class="svz-features">
        <?php foreach ($page->features()->toStructure() as $feature): ?>
            <li class="svz-feature svz-reveal">
                <span class="svz-feature__icon"><i class="fa <?= esc($feature->icon());?>" aria-hidden="true"></i></span>
                <h3 class="svz-feature__title"><?= $feature->title()->esc();?></h3>
                <p class="svz-feature__text"><?= $feature->text()->esc();?></p>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php if ($exterior): ?>
    <figure class="svz-exterior svz-reveal">
        <img src="<?= $exterior->url();?>" alt="Außenansicht des Sportvereinszentrums Bronnen mit Vereinswappen" loading="lazy" width="<?= $exterior->width();?>" height="<?= $exterior->height();?>">
        <figcaption><i class="fa fa-map-marker" aria-hidden="true"></i> Das SVZ der Sportfreunde Bronnen</figcaption>
    </figure>
<?php endif; ?>

<?php if ($imageCount > 0): ?>
    <div class="svz-section-head svz-section-head--spaced svz-reveal">
        <p class="svz-eyebrow">Einblicke</p>
        <h2 class="svz-title">Räume, Terrasse &amp; Spielplatz</h2>
    </div>
    <?php
    // The first image is a 2x2 tile followed by 4 small ones (= 5 images). The grid has 4 columns, so the last
    // images become wide tiles if the rest does not fill the last row.
    $leftover = max(0, $imageCount - 5) % 4;
    $tailCount = [1 => 1, 2 => 2, 3 => 1][$leftover] ?? 0;
    $tailClass = $leftover === 1 ? 'is-full' : 'is-wide';
    ?>
    <div class="svz-gallery" data-svz-gallery>
        <?php foreach ($images->values() as $index => $image): ?>
            <?php
            $classes = ['svz-gallery__item', 'svz-reveal'];
            if ($index === 0 && $imageCount > 1) {
                $classes[] = 'is-feature';
            } elseif ($tailCount > 0 && $index > 4 && $index >= $imageCount - $tailCount) {
                $classes[] = $tailClass;
            }
            $caption = $image->description()->isNotEmpty() ? $image->description()->value() : $image->caption()->value();
            ?>
            <a href="<?= $image->url();?>" class="<?= implode(' ', $classes);?>" title="<?= esc($caption);?>">
                <img src="<?= $image->resize($index === 0 ? 1000 : 600)->url();?>" alt="<?= esc($caption);?>" loading="lazy">
                <span class="svz-gallery__caption"><?= esc($image->caption()->isNotEmpty() ? $image->caption()->value() : $caption);?></span>
                <span class="svz-gallery__zoom" aria-hidden="true"><i class="fa fa-search-plus"></i></span>
            </a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if ($page->facts()->isNotEmpty()): ?>
    <section class="svz-facts svz-reveal" aria-labelledby="svz-facts-title">
        <div class="svz-facts__intro">
            <p class="svz-eyebrow">Ausstattung</p>
            <h2 class="svz-title" id="svz-facts-title">Kurz und knackig</h2>
            <p class="svz-lead">Alles Wichtige auf einen Blick.</p>
        </div>
        <div class="svz-facts__list"><?= $page->facts()->kirbytext();?></div>
    </section>
<?php endif; ?>

<?php if ($page->quotes()->isNotEmpty()): ?>
    <div class="svz-section-head svz-section-head--spaced svz-reveal">
        <p class="svz-eyebrow">Stimmen</p>
        <h2 class="svz-title">Das sagen unsere Gäste</h2>
    </div>
    <div class="svz-quotes">
        <?php foreach ($page->quotes()->toStructure() as $quote): ?>
            <figure class="svz-quote svz-reveal">
                <i class="fa fa-quote-left svz-quote__mark" aria-hidden="true"></i>
                <p><?= $quote->quote()->esc();?></p>
            </figure>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
