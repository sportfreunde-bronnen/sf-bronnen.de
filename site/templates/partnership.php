<?php
/**
 * Digital partnership request of the Förderverein ("Partnerschaft").
 *
 * The recipient of the request is configured in the config.php (option sfb.partnership.recipient),
 * see plugin sfb-partnership. The design is shared with the membership application (stylesheet membership.css).
 *
 * @var Kirby\Cms\Page $page
 * @var Uniform\Form $form
 * @var array $interests
 */

$privacyUrl = '/home/datenschutz';

// every message only once (a field can break several rules)
$errors = [];
foreach ($form->errors() as $messages) {
    foreach ((array) $messages as $message) {
        $errors[$message] = $message;
    }
}

$invalid = fn(string $field): string => $form->error($field) !== [] ? ' is-invalid' : '';

$selectedInterests = array_map('strval', (array) $form->old('interessen', []));
?>
<?php snippet('header', ['styles' => ['membership', 'partnership']]); ?>
<div class="page template--membership template--partnership">
    <section class="main-banner text-xs-center text-sm-center text-md-start">
        <div class="container text-lite-color ps-sm-0 pe-sm-0">
            <h1 class="text-weight-medium"><?= $page->title() . APPEND_TITLE;?></h1>
        </div>
    </section>
    <div class="main-container container ps-sm-0 pe-sm-0">
        <div class="row text-md-start">
            <div class="col-12">
                <?php if ($page->headline()->isNotEmpty()): ?>
                    <h2 class="main-heading-1 text-spl-color text-uppercase text-weight-normal text-center text-md-start"><?= $page->headline();?></h2>
                <?php endif; ?>
                <div class="contact-form-wrap membership-wrap">
                    <?php if ($page->text()->isNotEmpty()): ?>
                        <div class="membership-intro"><?= $page->text()->kirbytext();?></div>
                        <hr/>
                    <?php endif; ?>

                    <?php if ($form->success()): ?>
                        <div class="alert alert-success mb-5" role="status">
                            <strong>Vielen Dank für Ihr Interesse an einer Partnerschaft!</strong><br/>
                            Wir haben Ihre Anfrage erhalten. Der Förderverein nimmt in Kürze Kontakt mit Ihnen auf.
                        </div>
                    <?php else: ?>
                        <?php if (option('debug') === true && PartnershipRequest::recipient() === null): ?>
                            <div class="alert alert-warning" role="status">
                                Nur im Debug-Modus sichtbar: Die Empfängeradresse ist nicht eingestellt. Bitte <code>sfb.partnership.recipient</code> in der <code>config.php</code> setzen, sonst wird keine Anfrage versendet.
                            </div>
                        <?php endif; ?>

                        <?php if ($errors !== []): ?>
                            <div class="alert alert-danger" role="alert">
                                <strong>Ihre Anfrage konnte noch nicht gesendet werden:</strong>
                                <ul class="mb-0">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?= esc($error);?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form id="partnership-form" class="membership-form partnership-form" name="partnership-form" method="post" role="form">

                            <fieldset class="membership-section">
                                <legend>Wir haben Interesse an *</legend>
                                <p class="membership-hint">Eine Mehrfachauswahl ist möglich.</p>
                                <div class="partnership-interests<?= $invalid('interessen');?>">
                                    <?php foreach ($interests as $key => $title): ?>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="p-interesse-<?= $key;?>" name="interessen[]" value="<?= $key;?>"<?= in_array((string) $key, $selectedInterests, true) ? ' checked' : '';?>>
                                            <label class="form-check-label" for="p-interesse-<?= $key;?>"><?= esc($title);?></label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </fieldset>

                            <fieldset class="membership-section">
                                <legend>Firma und Ansprechpartner</legend>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="p-firma">Firma</label>
                                            <input type="text" class="form-control flat<?= $invalid('firma');?>" id="p-firma" name="firma" value="<?= $form->old('firma');?>" maxlength="150" autocomplete="organization">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="p-ansprechpartner">Ansprechpartner *</label>
                                            <input type="text" class="form-control flat<?= $invalid('ansprechpartner');?>" id="p-ansprechpartner" name="ansprechpartner" value="<?= $form->old('ansprechpartner');?>" maxlength="150" autocomplete="name" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="p-strasse">Straße, Hausnummer *</label>
                                            <input type="text" class="form-control flat<?= $invalid('strasse');?>" id="p-strasse" name="strasse" value="<?= $form->old('strasse');?>" maxlength="100" autocomplete="street-address" required>
                                        </div>
                                    </div>
                                    <div class="col-5 col-md-3">
                                        <div class="mb-3">
                                            <label for="p-plz">PLZ *</label>
                                            <input type="text" class="form-control flat<?= $invalid('plz');?>" id="p-plz" name="plz" value="<?= $form->old('plz');?>" inputmode="numeric" pattern="[0-9]{5}" maxlength="5" autocomplete="postal-code" required>
                                        </div>
                                    </div>
                                    <div class="col-7 col-md-9">
                                        <div class="mb-3">
                                            <label for="p-ort">Ort *</label>
                                            <input type="text" class="form-control flat<?= $invalid('ort');?>" id="p-ort" name="ort" value="<?= $form->old('ort');?>" maxlength="100" autocomplete="address-level2" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="p-telefon">Telefon/Mobil</label>
                                            <input type="tel" class="form-control flat<?= $invalid('telefon');?>" id="p-telefon" name="telefon" value="<?= $form->old('telefon');?>" maxlength="30" autocomplete="tel">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="p-email">E-Mail *</label>
                                            <input type="email" class="form-control flat<?= $invalid('email');?>" id="p-email" name="email" value="<?= $form->old('email');?>" maxlength="200" autocomplete="email" required>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="membership-section">
                                <legend>Anmerkungen</legend>
                                <div class="mb-3">
                                    <label for="p-nachricht">Haben Sie Anmerkungen oder Wünsche, z. B. zum Zweck einer zweckgebundenen Spende?</label>
                                    <textarea class="form-control flat<?= $invalid('nachricht');?>" id="p-nachricht" name="nachricht" rows="5" maxlength="5000"><?= $form->old('nachricht');?></textarea>
                                </div>
                            </fieldset>

                            <fieldset class="membership-section">
                                <legend>Datenschutz</legend>
                                <div class="form-check membership-consent<?= $invalid('dataDeclaration');?>">
                                    <input type="checkbox" class="form-check-input" id="p-datenschutz" name="dataDeclaration" value="1" required>
                                    <label class="form-check-label" for="p-datenschutz">
                                        * Ich erkläre mich damit einverstanden, dass der Förderverein Sportfreunde Bronnen e.V. meine Angaben zur Bearbeitung meiner Anfrage und zur Kontaktaufnahme verwendet.
                                        Eine Weitergabe an Dritte findet grundsätzlich nicht statt, es sei denn geltende Datenschutzvorschriften rechtfertigen eine Übertragung oder der Verein ist dazu gesetzlich verpflichtet.
                                        Ich kann meine Einwilligung jederzeit mit Wirkung für die Zukunft widerrufen. Im Falle des Widerrufs werden meine Daten umgehend gelöscht.
                                        Weitere Informationen zum Datenschutz finden Sie auch in der <a href="<?= $privacyUrl;?>" target="_blank" rel="noopener" title="Zur Datenschutzerklärung der Sportfreunde Bronnen">Datenschutzerklärung</a> dieser Webseite.
                                    </label>
                                </div>
                            </fieldset>

                            <p class="membership-hint">
                                * = Pflichtfeld. Das Datum Ihrer Anfrage wird automatisch erfasst, die Einwilligung ersetzt die Unterschrift.
                            </p>

                            <div class="membership-turnstile mb-3">
                                <?php snippet('turnstile', ['action' => 'partnership']); ?>
                            </div>

                            <input type="submit" class="btn btn-1 btn-big animation col-12 col-md-4" value="Anfrage abschicken">

                            <?php echo csrf_field(); ?>
                            <?php echo honeypot_field(); ?>
                        </form>
                    <?php endif; ?>
                </div>
                <?php snippet('people'); ?>
            </div>
        </div>
    </div>
</div>
<?php snippet('footer'); ?><?php snippet('close'); ?>
