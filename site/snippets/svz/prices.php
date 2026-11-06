<?php
/**
 * Price overview of the SVZ.
 *
 * @var SvzPage $page
 */

$groups = $page->priceGroups();
?>
<div class="svz-section-head svz-reveal">
    <p class="svz-eyebrow">Preisübersicht</p>
    <h2 class="svz-title">Das passende Paket für Ihre Feier</h2>
    <p class="svz-lead">Wählen Sie zwischen Getränkepaket und Raumpaket, jeweils für den kleinen oder den großen Wirtschaftsraum.</p>
</div>

<?php if ($groups === []): ?>
    <div class="svz-notice" role="status">
        <i class="fa fa-info-circle" aria-hidden="true"></i>
        <span>Die Preise erfragen Sie bitte direkt über unser <a href="<?= SvzDomain::link('home/kontakt');?>?rec=svz">Kontaktformular</a>.</span>
    </div>
<?php else: ?>

    <div class="svz-tariff svz-reveal" data-svz-tariff-switch hidden>
        <span class="svz-tariff__label" id="svz-tariff-label">Ihr Tarif:</span>
        <div class="svz-tariff__options" role="group" aria-labelledby="svz-tariff-label">
            <button type="button" class="is-active" data-svz-tariff="regular" aria-pressed="true">Externe</button>
            <button type="button" data-svz-tariff="member" aria-pressed="false">Aktive Mitglieder</button>
        </div>
    </div>

    <?php foreach ($groups as $group): ?>
        <section class="svz-package svz-reveal" aria-labelledby="svz-package-<?= $group['key'];?>">
            <header class="svz-package__head">
                <span class="svz-package__icon"><i class="fa <?= $group['icon'];?>" aria-hidden="true"></i></span>
                <div>
                    <h3 class="svz-package__title" id="svz-package-<?= $group['key'];?>"><?= $group['title'];?></h3>
                    <p class="svz-package__subtitle"><?= $group['subtitle'];?></p>
                </div>
            </header>
            <p class="svz-package__text"><?= $group['description'];?></p>

            <div class="svz-package__grid">
                <?php foreach ($group['rows'] as $row): ?>
                    <?php
                    $hasMinimum = $row['minimum'] > 0;
                    $prices = [
                        'regular' => [
                            'label' => 'Externe',
                            'total' => $row['total'],
                            'rent' => $row['rent'],
                        ],
                        'member' => [
                            'label' => 'Aktive Mitglieder',
                            'total' => $row['memberTotal'],
                            'rent' => $row['memberRent'],
                        ],
                    ];
                    ?>
                    <article class="svz-price-card">
                        <header class="svz-price-card__head">
                            <h4><?= $row['room']['title'];?> <small><?= $row['room']['subtitle'];?></small></h4>
                            <ul class="svz-price-card__meta">
                                <li><i class="fa fa-arrows-alt" aria-hidden="true"></i> <?= $row['room']['size'];?></li>
                                <li><i class="fa fa-users" aria-hidden="true"></i> <?= $row['room']['capacity'];?></li>
                            </ul>
                        </header>

                        <?php foreach ($prices as $tariff => $price): ?>
                            <div class="svz-price" data-svz-price="<?= $tariff;?>">
                                <span class="svz-price__label"><?= $price['label'];?></span>
                                <span class="svz-price__total"><?= SvzPage::formatPrice($price['total']);?></span>
                                <span class="svz-price__breakdown">
                                    <?php if ($hasMinimum): ?>
                                        <?= SvzPage::formatPrice($price['rent']);?> Raummiete + <?= SvzPage::formatPrice($row['minimum']);?> Mindestverzehr
                                    <?php else: ?>
                                        Raummiete
                                    <?php endif; ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endforeach; ?>

    <?php if ($page->priceNotes()->isNotEmpty()): ?>
        <div class="svz-notice svz-reveal">
            <i class="fa fa-info-circle" aria-hidden="true"></i>
            <div><?= $page->priceNotes()->kirbytext();?></div>
        </div>
    <?php endif; ?>

<?php endif; ?>
