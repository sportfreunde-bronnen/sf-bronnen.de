<?php
/**
 * Annual membership fees and the fee calculator (see BeitraegePage::calculator() and public/assets/js/fee-calculator.js).
 *
 * @var Kirby\Cms\Page $page
 */

$calculator = $page->calculator();

// one person of the calculator, the index "__INDEX__" is used for the template of the persons added by the script
$calculatorPerson = function ($index) use ($calculator): void { ?>
    <div class="fee-calculator__person" data-fee-person>
        <div class="fee-calculator__person-head" data-fee-person-head>
            <span class="fee-calculator__person-title" data-fee-person-title>Mitglied</span>
            <button type="button" class="btn btn-sm btn-outline-secondary" data-fee-remove>
                <i class="fa fa-times" aria-hidden="true"></i> Entfernen
            </button>
        </div>
        <div class="fee-calculator__departments">
            <?php foreach ($calculator['departments'] as $key => $department): ?>
                <div class="custom-control custom-checkbox fee-calculator__department" data-fee-department="<?= $key;?>" hidden>
                    <input type="checkbox" class="custom-control-input" id="fee-person-<?= $index;?>-<?= $key;?>" value="<?= $key;?>" data-fee-department-input>
                    <label class="custom-control-label" for="fee-person-<?= $index;?>-<?= $key;?>">
                        <?= esc($department['label']);?>
                        <span class="fee-calculator__price" data-fee-price></span>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php };
?>
<?php snippet('header', $calculator ? ['styles' => ['fee-calculator']] : []); ?>
<section class="main-banner text-xs-center text-sm-center text-md-left">
    <div class="container text-lite-color pl-sm-0 pr-sm-0">
        <h1 class="text-weight-medium"><?= $page->title() . APPEND_TITLE;; ?></h1>
    </div>
</section>
<div class="page">
    <div class="main-container container pl-sm-0 pr-sm-0">
        <h2 class="main-heading-1 text-spl-color text-weight-normal text-uppercase text-center text-md-left"><?= $page->headline();?></h2>
        <p><?= $page->text()->kirbytext();?></p>
        <div class="row">
            <?php foreach ($page->beitraege()->toStructure() as $beitrag): ?>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="pricing-table text-center animation">
                        <div class="plan-name">
                            <h5 class="text-weight-medium"><?= $beitrag->name();?></h5>
                        </div>
                        <div class="plan-price">
                            <h3><?= number_format($beitrag->grundbeitrag()->float(), 2, ',', '.');?> € <?= $beitrag->grundbeitragzusatz();?></h3>
                            <span class="h5 text-weight-thin">Grundbeitrag</span>
                        </div>
                        <div class="plan-list">
                            <ul class="list-unstyled">
                                <li class="animation">
                                    <h5><?= number_format($beitrag->fussball()->float(), 2, ',', '.'); ?> € <?= $beitrag->fussballzusatz();?></h5>
                                    Beitrag Fußball
                                </li>
                                <li class="animation">
                                    <h5><?= number_format($beitrag->gymnastik()->float(), 2, ',', '.'); ?> € <?= $beitrag->gymnastikzusatz();?></h5>
                                    Beitrag Gymnastik
                                </li>
                                <li class="animation">
                                    <h5><?= number_format($beitrag->aikido()->float(), 2, ',', '.'); ?> € <?= $beitrag->aikidozusatz();?></h5>
                                    Beitrag Aikido
                                </li>
                                <li class="animation">
                                  <h5><?= number_format($beitrag->darts()->float(), 2, ',', '.'); ?> € <?= $beitrag->dartszusatz();?></h5>
                                  Beitrag Darts
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?= $page->zusatz()->kirbytext();?>

        <?php if ($calculator): ?>
            <section class="fee-calculator" id="beitragsrechner" data-fee-calculator data-config="<?= htmlspecialchars(json_encode($calculator, JSON_UNESCAPED_UNICODE), ENT_QUOTES);?>" hidden>
                <h2 class="main-heading-1 text-spl-color text-weight-normal text-uppercase text-center text-md-left">Beitragsrechner</h2>
                <p class="fee-calculator__intro">Berechnen Sie hier unverbindlich Ihren Jahresbeitrag: Wählen Sie die Beitragsklasse und die Abteilung(en), in denen Sie aktiv sein möchten.</p>

                <div class="row">
                    <div class="col-lg-7">
                        <fieldset class="fee-calculator__step">
                            <legend>1. Beitragsklasse</legend>
                            <div class="fee-calculator__classes">
                                <?php foreach ($calculator['classes'] as $key => $class): ?>
                                    <div class="custom-control custom-radio fee-calculator__class">
                                        <input type="radio" class="custom-control-input" id="fee-class-<?= $key;?>" name="fee-class" value="<?= $key;?>">
                                        <label class="custom-control-label" for="fee-class-<?= $key;?>">
                                            <strong><?= esc($class['label']);?></strong>
                                            <span class="fee-calculator__class-hint"><?= esc($class['hint']);?></span>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </fieldset>

                        <fieldset class="fee-calculator__step" data-fee-departments hidden>
                            <legend>2. Abteilung(en)</legend>
                            <p class="fee-calculator__hint" data-fee-family-hint hidden>
                                Der Grundbeitrag gilt einmal pro Familie. Wählen Sie für jedes Familienmitglied die Abteilung(en); die Abteilungsbeiträge fallen pro Kopf an.
                            </p>
                            <p class="fee-calculator__hint">Eine Mehrfachauswahl ist möglich. Ohne Auswahl fällt nur der Grundbeitrag an.</p>
                            <div data-fee-persons></div>
                            <button type="button" class="btn btn-outline-primary fee-calculator__add" data-fee-add hidden>
                                <i class="fa fa-plus" aria-hidden="true"></i> Weiteres Familienmitglied hinzufügen
                            </button>
                            <template data-fee-person-template><?php $calculatorPerson('__INDEX__'); ?></template>
                        </fieldset>

                        <p class="fee-calculator__hint" data-fee-no-departments hidden>Passive Mitglieder zahlen nur den Grundbeitrag, eine Abteilung kann nicht gewählt werden.</p>
                    </div>
                    <div class="col-lg-5">
                        <div class="fee-calculator__result" data-fee-result aria-live="polite"></div>
                    </div>
                </div>

                <p class="fee-calculator__disclaimer">Alle Angaben ohne Gewähr, maßgeblich sind die oben genannten Beiträge. Möchten Sie Mitglied werden? Hier geht es zur <a href="<?= page('gesamtverein/mitglieder/mitglied-werden')?->url() ?? '/home/kontakt';?>">Mitgliedschaft</a>.</p>
            </section>
        <?php endif; ?>
    </div>
</div>
<?php snippet('footer', $calculator ? ['scripts' => ['fee-calculator']] : []); ?><?php snippet('close'); ?>