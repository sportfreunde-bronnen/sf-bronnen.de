<?php
/**
 * Sketch of the floor plan of the Sportvereinszentrum (SVZ), subpage "grundriss" of the SVZ page.
 *
 * The rooms and their dimensions are defined in SvzFloorplan (plugin sfb-svz), the sketch is drawn by the
 * snippet svz/floorplan. The page is linked in the "Infos" section of the SVZ page (see snippet svz/info).
 *
 * @var SvzplanPage $page
 */

$svzUrl = $page->parent()->url();
$contactUrl = SvzDomain::contactUrl();
$rooms = SvzFloorplan::rooms();
$groups = SvzFloorplan::groups();
?>
<?php snippet('header', ['styles' => ['svz']]); ?>
<div class="page page--svz">
<div class="svz svz--form svz--plan">

    <section class="svz-formhead">
        <div class="container">
            <a href="<?= $svzUrl;?>" class="svz-formhead__back"><i class="fa fa-angle-left" aria-hidden="true"></i> Zurück zum Sportvereinszentrum</a>
            <p class="svz-eyebrow svz-eyebrow--light">Sportvereinszentrum Bronnen</p>
            <h1 class="svz-formhead__title"><?= $page->title() . APPEND_TITLE;?></h1>
            <?php if ($page->headline()->isNotEmpty()): ?>
                <p class="svz-formhead__lead"><?= $page->headline()->esc();?></p>
            <?php endif; ?>
        </div>
    </section>

    <section class="svz-formwrap">
        <div class="container">
            <div class="svz-formcard">
                <?php if ($page->text()->isNotEmpty()): ?>
                    <div class="svz-lead svz-formcard__intro"><?= $page->text()->kirbytext();?></div>
                <?php endif; ?>

                <figure class="svz-plan">
                    <div class="svz-plan__scroll">
                        <?php snippet('svz/floorplan', ['rooms' => $rooms]); ?>
                    </div>
                    <p class="svz-plan__hint"><i class="fa fa-arrows-h" aria-hidden="true"></i> Die Skizze lässt sich seitlich verschieben. Alle Maße stehen auch in der Tabelle.</p>
                    <figcaption class="svz-plan__legend">
                        <ul>
                            <?php foreach ($groups as $key => $label): ?>
                                <li><span class="svz-plan__swatch svz-plan__swatch--<?= $key;?>" aria-hidden="true"></span><?= esc($label);?></li>
                            <?php endforeach; ?>
                            <li><span class="svz-plan__swatch svz-plan__swatch--other" aria-hidden="true"></span>weitere Räume (nicht dargestellt)</li>
                            <li><span class="svz-plan__swatch svz-plan__swatch--door" aria-hidden="true"></span>Tür / Durchgang</li>
                            <li><span class="svz-plan__swatch svz-plan__swatch--movable" aria-hidden="true"></span>einfahrbare Wand</li>
                            <li><span class="svz-plan__swatch svz-plan__swatch--theke" aria-hidden="true"></span>Theke</li>
                            <li><span class="svz-plan__swatch svz-plan__swatch--stool" aria-hidden="true"></span>Barhocker</li>
                        </ul>
                        <p>Maße in Metern (Breite × Tiefe), Flächen in m². Die gestrichelte Wand zwischen Gymnastik und Gastraum lässt sich einfahren, beide Räume werden dann zu einem großen Saal. Im Gastraum steht die Theke mit <?= count(SvzFloorplan::stools());?> Barhockern (Kreise).</p>
                    </figcaption>
                </figure>

                <h2 class="svz-plan__heading">Räume im Überblick</h2>
                <div class="svz-plan__tablewrap">
                    <table class="svz-plan__table">
                        <thead>
                            <tr>
                                <th scope="col">Raum</th>
                                <th scope="col">Maße</th>
                                <th scope="col">Fläche</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rooms as $room): ?>
                                <tr>
                                    <th scope="row"><span class="svz-plan__name"><span class="svz-plan__swatch svz-plan__swatch--<?= $room['group'];?>" aria-hidden="true"></span><?= esc($room['name']);?></span></th>
                                    <td><?= implode(' m<br>', array_map('esc', SvzFloorplan::dimensions($room)));?> m</td>
                                    <td><?= SvzFloorplan::area($room);?> m²</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <p class="svz-plan__note">
                    Grundlage ist der Bauplan des SVV (Grundriss M 1:100). Die Skizze zeigt nur die Räume,
                    die für Gäste interessant sind. Die Maße sind lichte Innenmaße. Die Flächen stammen aus der Nutzflächenaufstellung des Plans (DIN 277). Wegen Nischen und
                    Türöffnungen können sie leicht von Breite × Tiefe abweichen, zum Beispiel bei Geräte und Gastraum.
                </p>

                <div class="svz-actions">
                    <a href="<?= $svzUrl;?>#verfuegbarkeit" class="svz-btn svz-btn--primary"><i class="fa fa-calendar-check-o" aria-hidden="true"></i> Verfügbarkeit prüfen</a>
                    <a href="<?= $contactUrl;?>" class="svz-btn svz-btn--accent"><i class="fa fa-envelope-o" aria-hidden="true"></i> Jetzt anfragen</a>
                </div>
            </div>
        </div>
    </section>

</div>
</div>
<?php snippet('footer'); ?><?php snippet('close'); ?>
