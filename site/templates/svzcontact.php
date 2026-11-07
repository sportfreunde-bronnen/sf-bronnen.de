<?php
/**
 * Contact form of the Sportvereinszentrum (SVZ).
 *
 * The date is preselected via ?datum=YYYY-MM-DD, see the button below the availability calendar.
 * The recipient of the request is configured in the config.php (option sfb.svz.recipient).
 *
 * @var SvzcontactPage $page
 * @var Uniform\Form $form
 * @var array $packages
 * @var string $minDate
 * @var string $presetDate
 */

$svzUrl = $page->parent()->url();
$privacyUrl = SvzDomain::link('home/datenschutz');

// every message only once (a field can break several rules)
$errors = [];
foreach ($form->errors() as $messages) {
    foreach ((array) $messages as $message) {
        $errors[$message] = $message;
    }
}

$invalid = fn(string $field): string => $form->error($field) !== [] ? ' is-invalid' : '';
$choices = ['ja' => 'Ja', 'nein' => 'Nein'];
$selectedPackage = $form->old('paket');
?>
<?php snippet('header', ['styles' => ['svz']]); ?>
<div class="page page--svz">
<div class="svz svz--form">

    <section class="svz-formhead">
        <div class="container">
            <a href="<?= $svzUrl;?>#verfuegbarkeit" class="svz-formhead__back"><i class="fa fa-angle-left" aria-hidden="true"></i> Zurück zur Verfügbarkeit</a>
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

                <?php if ($form->success()): ?>
                    <div class="svz-notice svz-notice--success" role="status">
                        <i class="fa fa-check-circle" aria-hidden="true"></i>
                        <div>
                            <p><strong>Vielen Dank für Ihre Anfrage!</strong></p>
                            <p>Wir haben Ihre Anfrage erhalten und werden uns umgehend mit Ihnen in Verbindung setzen.</p>
                        </div>
                    </div>
                    <div class="svz-actions">
                        <a href="<?= $svzUrl;?>" class="svz-btn svz-btn--primary"><i class="fa fa-angle-left" aria-hidden="true"></i> Zurück zum Sportvereinszentrum</a>
                    </div>
                <?php else: ?>
                    <?php if (option('debug') === true && SvzDomain::recipient() === null): ?>
                        <div class="svz-notice svz-notice--warning" role="status">
                            <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                            <span>Nur im Debug-Modus sichtbar: Die Empfängeradresse ist nicht eingestellt. Bitte <code>sfb.svz.recipient</code> in der <code>config.php</code> setzen, sonst wird keine Anfrage versendet.</span>
                        </div>
                    <?php endif; ?>

                    <?php if ($errors !== []): ?>
                        <div class="svz-notice svz-notice--error" role="alert">
                            <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                            <div>
                                <p><strong>Ihre Anfrage konnte noch nicht gesendet werden:</strong></p>
                                <ul>
                                    <?php foreach ($errors as $error): ?>
                                        <li><?= esc($error);?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form id="svz-contact-form" class="svz-form" method="post" role="form">

                        <fieldset class="svz-form__group">
                            <legend>Ihr Wunschtermin</legend>
                            <div class="svz-form__row">
                                <div class="svz-field svz-field--date">
                                    <label for="svz-datum">Datum *</label>
                                    <input type="date" class="svz-input<?= $invalid('datum');?>" id="svz-datum" name="datum" min="<?= $minDate;?>" value="<?= $form->old('datum', $presetDate);?>" required>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="svz-form__group">
                            <legend>Paket *</legend>
                            <div class="svz-choices svz-choices--cards<?= $invalid('paket');?>">
                                <?php foreach ($packages as $key => $package): ?>
                                    <label class="svz-choice">
                                        <input type="radio" name="paket" value="<?= $key;?>"<?= $selectedPackage === $key ? ' checked' : '';?> required>
                                        <span class="svz-choice__box">
                                            <i class="fa <?= $package['icon'];?>" aria-hidden="true"></i>
                                            <span class="svz-choice__title"><?= esc($package['title']);?></span>
                                            <span class="svz-choice__text"><?= esc($package['subtitle']);?></span>
                                        </span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </fieldset>

                        <div class="svz-form__row svz-form__row--split">
                            <fieldset class="svz-form__group">
                                <legend>Aktives Mitglied? *</legend>
                                <div class="svz-choices<?= $invalid('mitglied');?>">
                                    <?php foreach ($choices as $value => $label): ?>
                                        <label class="svz-choice">
                                            <input type="radio" name="mitglied" value="<?= $value;?>"<?= $form->old('mitglied') === $value ? ' checked' : '';?> required>
                                            <span class="svz-choice__box"><span class="svz-choice__title"><?= $label;?></span></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                                <p class="svz-form__hint">Vereinsmitglieder der Sportfreunde Bronnen (seit mindestens einem Jahr im Verein) erhalten einen reduzierten Tarif.</p>
                            </fieldset>

                            <fieldset class="svz-form__group">
                                <legend>Wünschen Sie eine Servicekraft? *</legend>
                                <div class="svz-choices<?= $invalid('servicekraft');?>">
                                    <?php foreach ($choices as $value => $label): ?>
                                        <label class="svz-choice">
                                            <input type="radio" name="servicekraft" value="<?= $value;?>"<?= $form->old('servicekraft') === $value ? ' checked' : '';?> required>
                                            <span class="svz-choice__box"><span class="svz-choice__title"><?= $label;?></span></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </fieldset>
                        </div>

                        <fieldset class="svz-form__group">
                            <legend>Ihre Kontaktdaten</legend>
                            <div class="svz-form__row svz-form__row--split">
                                <div class="svz-field">
                                    <label for="svz-vorname">Vorname *</label>
                                    <input type="text" class="svz-input<?= $invalid('vorname');?>" id="svz-vorname" name="vorname" value="<?= $form->old('vorname');?>" autocomplete="given-name" maxlength="100" required>
                                </div>
                                <div class="svz-field">
                                    <label for="svz-nachname">Nachname *</label>
                                    <input type="text" class="svz-input<?= $invalid('nachname');?>" id="svz-nachname" name="nachname" value="<?= $form->old('nachname');?>" autocomplete="family-name" maxlength="100" required>
                                </div>
                            </div>
                            <div class="svz-form__row svz-form__row--split">
                                <div class="svz-field">
                                    <label for="svz-email">E-Mail *</label>
                                    <input type="email" class="svz-input<?= $invalid('email');?>" id="svz-email" name="email" value="<?= $form->old('email');?>" autocomplete="email" maxlength="200" required>
                                </div>
                                <div class="svz-field">
                                    <label for="svz-telefon">Telefon</label>
                                    <input type="tel" class="svz-input<?= $invalid('telefon');?>" id="svz-telefon" name="telefon" value="<?= $form->old('telefon');?>" autocomplete="tel" maxlength="30">
                                </div>
                            </div>
                            <div class="svz-field">
                                <label for="svz-nachricht">Ihre Nachricht *</label>
                                <textarea class="svz-input<?= $invalid('nachricht');?>" id="svz-nachricht" name="nachricht" rows="7" maxlength="5000" placeholder="z.B. Art der Veranstaltung, Anzahl der Gäste, gewünschter Raum" required><?= $form->old('nachricht');?></textarea>
                            </div>
                        </fieldset>

                        <div class="svz-form__consent<?= $invalid('dataDeclaration');?>">
                            <label class="svz-check">
                                <input type="checkbox" id="svz-consent" name="dataDeclaration" value="1" required>
                                <span>
                                    * Ich erkläre mich damit einverstanden, dass die Sportfreunde Bronnen 1949 e.V. meine Angaben für die Beantwortung meiner Anfrage bzw. Kontaktaufnahme verwenden.
                                    Eine Weitergabe an Dritte findet grundsätzlich nicht statt, es sei denn geltende Datenschutzvorschriften rechtfertigen eine Übertragung oder die Sportfreunde Bronnen 1949 e.V. sind dazu gesetzlich verpflichtet. Sie können Ihre erteilte Einwilligung jederzeit mit Wirkung für die Zukunft widerrufen. Im Falle des Widerrufs werden Ihre Daten umgehend gelöscht. Ihre Daten werden ansonsten gelöscht, wenn wir Ihre Anfrage bearbeitet haben oder der Zweck der Speicherung entfallen ist. Sie können sich jederzeit über die zu Ihrer Person gespeicherten Daten informieren. Weitere Informationen zum Datenschutz finden Sie auch in der <a href="<?= $privacyUrl;?>" title="Zur Datenschutzerklärung der Sportfreunde Bronnen">Datenschutzerklärung</a> dieser Webseite.
                                </span>
                            </label>
                        </div>

                        <p class="svz-form__hint">* = Pflichtfeld</p>

                        <div class="svz-form__turnstile">
                            <?php snippet('turnstile', ['action' => 'svz-contact']); ?>
                        </div>

                        <div class="svz-actions">
                            <button type="submit" class="svz-btn svz-btn--accent svz-btn--large"><i class="fa fa-paper-plane-o" aria-hidden="true"></i> Anfrage senden</button>
                        </div>

                        <?php echo csrf_field(); ?>
                        <?php echo honeypot_field(); ?>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </section>

</div>
</div>
<?php snippet('footer'); ?><?php snippet('close'); ?>
