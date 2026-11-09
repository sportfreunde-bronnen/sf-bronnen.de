<?php
/**
 * Digital membership application ("Mitglied werden").
 *
 * The recipient of the application (membership administration) is configured in the config.php
 * (option sfb.membership.recipient), see plugin sfb-membership.
 *
 * @var Kirby\Cms\Page $page
 * @var Uniform\Form $form
 * @var array $departments
 * @var array $feeClasses
 * @var int $maxFamilyMembers
 * @var string $today
 */

$privacyUrl = '/home/datenschutz';
$feeUrl = page('gesamtverein/allgemeines/beitraege')?->url();

// every message only once (a field can break several rules)
$errors = [];
foreach ($form->errors() as $messages) {
    foreach ((array) $messages as $message) {
        $errors[$message] = $message;
    }
}

$invalid = fn(string $field): string => $form->error($field) !== [] ? ' is-invalid' : '';

$selectedGroups = array_map('strval', (array) $form->old('gruppen', []));
$selectedFeeClass = $form->old('beitragsklasse');

// additional family members: the submitted rows, otherwise one empty row
$oldFamily = $form->old('familie', []);
$familyRows = is_array($oldFamily) && $oldFamily !== [] ? array_values($oldFamily) : [[]];

// group selection of a family member
$familyGroupSelect = function (string $name, string $selected, string $id) use ($departments): void { ?>
    <select class="form-select flat" id="<?= $id;?>" name="<?= $name;?>">
        <option value="">Bitte wählen</option>
        <option value="<?= MembershipApplication::PASSIVE;?>"<?= $selected === MembershipApplication::PASSIVE ? ' selected' : '';?>>Passive Mitgliedschaft</option>
        <?php foreach ($departments as $department): ?>
            <optgroup label="<?= esc($department['title'], 'attr');?>">
                <?php foreach ($department['groups'] as $key => $title): ?>
                    <option value="<?= $key;?>"<?= $selected === (string) $key ? ' selected' : '';?>><?= esc($title);?></option>
                <?php endforeach; ?>
            </optgroup>
        <?php endforeach; ?>
    </select>
<?php };

// one row of the additional family members, the index "__INDEX__" is used for the template of new rows
$familyRow = function ($index, array $member) use ($familyGroupSelect, $today): void {
    $field = fn(string $name): string => (string) ($member[$name] ?? ''); ?>
    <div class="membership-family-row" data-membership-family-row>
        <div class="membership-family-row__head">
            <span class="membership-family-row__title">Familienmitglied <span data-membership-family-number></span></span>
            <button type="button" class="btn btn-sm btn-outline-secondary membership-family-row__remove" data-membership-family-remove>
                <i class="fa fa-times" aria-hidden="true"></i> Entfernen
            </button>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="m-familie-<?= $index;?>-nachname">Nachname</label>
                    <input type="text" class="form-control flat" id="m-familie-<?= $index;?>-nachname" name="familie[<?= $index;?>][nachname]" value="<?= $field('nachname');?>" maxlength="100" autocomplete="off">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="m-familie-<?= $index;?>-vorname">Vorname</label>
                    <input type="text" class="form-control flat" id="m-familie-<?= $index;?>-vorname" name="familie[<?= $index;?>][vorname]" value="<?= $field('vorname');?>" maxlength="100" autocomplete="off">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="m-familie-<?= $index;?>-geburtsdatum">Geburtsdatum</label>
                    <input type="date" class="form-control flat" id="m-familie-<?= $index;?>-geburtsdatum" name="familie[<?= $index;?>][geburtsdatum]" value="<?= $field('geburtsdatum');?>" min="1900-01-01" max="<?= $today;?>" autocomplete="off">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="m-familie-<?= $index;?>-gruppe">Gruppe / Abteilung</label>
                    <?php $familyGroupSelect('familie[' . $index . '][gruppe]', $field('gruppe'), 'm-familie-' . $index . '-gruppe'); ?>
                </div>
            </div>
        </div>
    </div>
<?php };
?>
<?php snippet('header', ['styles' => ['membership']]); ?>
<div class="page template--membership">
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
                            <strong>Vielen Dank für Ihren Mitgliedsantrag!</strong><br/>
                            Wir haben Ihren Antrag erhalten. Unsere Mitgliederverwaltung prüft Ihre Angaben und meldet sich bei Ihnen.
                        </div>
                    <?php else: ?>
                        <?php if (option('debug') === true && MembershipApplication::recipient() === null): ?>
                            <div class="alert alert-warning" role="status">
                                Nur im Debug-Modus sichtbar: Die Empfängeradresse ist nicht eingestellt. Bitte <code>sfb.membership.recipient</code> in der <code>config.php</code> setzen, sonst wird kein Antrag versendet.
                            </div>
                        <?php endif; ?>

                        <?php if ($errors !== []): ?>
                            <div class="alert alert-danger" role="alert">
                                <strong>Ihr Antrag konnte noch nicht gesendet werden:</strong>
                                <ul class="mb-0">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?= esc($error);?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form id="membership-form" class="membership-form" name="membership-form" method="post" role="form" data-membership-form>

                            <fieldset class="membership-section">
                                <legend>Persönliche Daten</legend>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="m-nachname">Name *</label>
                                            <input type="text" class="form-control flat<?= $invalid('nachname');?>" id="m-nachname" name="nachname" value="<?= $form->old('nachname');?>" maxlength="100" autocomplete="family-name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="m-vorname">Vorname *</label>
                                            <input type="text" class="form-control flat<?= $invalid('vorname');?>" id="m-vorname" name="vorname" value="<?= $form->old('vorname');?>" maxlength="100" autocomplete="given-name" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="m-strasse">Straße und Hausnummer *</label>
                                            <input type="text" class="form-control flat<?= $invalid('strasse');?>" id="m-strasse" name="strasse" value="<?= $form->old('strasse');?>" maxlength="100" autocomplete="street-address" required>
                                        </div>
                                    </div>
                                    <div class="col-5 col-md-3">
                                        <div class="mb-3">
                                            <label for="m-plz">PLZ *</label>
                                            <input type="text" class="form-control flat<?= $invalid('plz');?>" id="m-plz" name="plz" value="<?= $form->old('plz');?>" inputmode="numeric" pattern="[0-9]{5}" maxlength="5" autocomplete="postal-code" required>
                                        </div>
                                    </div>
                                    <div class="col-7 col-md-9">
                                        <div class="mb-3">
                                            <label for="m-ort">Ort *</label>
                                            <input type="text" class="form-control flat<?= $invalid('ort');?>" id="m-ort" name="ort" value="<?= $form->old('ort');?>" maxlength="100" autocomplete="address-level2" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="m-eintrittsdatum">Gewünschtes Eintrittsdatum *</label>
                                            <input type="date" class="form-control flat<?= $invalid('eintrittsdatum');?>" id="m-eintrittsdatum" name="eintrittsdatum" value="<?= $form->old('eintrittsdatum', $today);?>" min="<?= $today;?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="m-geburtsdatum">Geburtsdatum *</label>
                                            <input type="date" class="form-control flat<?= $invalid('geburtsdatum');?>" id="m-geburtsdatum" name="geburtsdatum" value="<?= $form->old('geburtsdatum');?>" min="1900-01-01" max="<?= $today;?>" autocomplete="bday" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="m-email">E-Mail Adresse *</label>
                                            <input type="email" class="form-control flat<?= $invalid('email');?>" id="m-email" name="email" value="<?= $form->old('email');?>" maxlength="200" autocomplete="email" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="m-telefon">Telefonnummer *</label>
                                            <input type="tel" class="form-control flat<?= $invalid('telefon');?>" id="m-telefon" name="telefon" value="<?= $form->old('telefon');?>" maxlength="30" autocomplete="tel" required>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="membership-section">
                                <legend>Gruppe bzw. Abteilung *</legend>
                                <p class="membership-hint">Bitte wählen Sie die Gruppe(n) bzw. Abteilung(en), in der bzw. denen Sie aktiv sein möchten, oder die passive Mitgliedschaft. Eine Mehrfachauswahl ist möglich.</p>
                                <div class="membership-groups<?= $invalid('gruppen');?>">
                                    <div class="membership-department">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="m-gruppe-passiv" name="gruppen[]" value="<?= MembershipApplication::PASSIVE;?>"<?= in_array(MembershipApplication::PASSIVE, $selectedGroups, true) ? ' checked' : '';?> data-membership-passive>
                                            <label class="form-check-label" for="m-gruppe-passiv"><strong>Passive Mitgliedschaft</strong></label>
                                        </div>
                                    </div>
                                    <?php foreach ($departments as $departmentKey => $department): ?>
                                        <div class="membership-department">
                                            <h3 class="membership-department__title"><?= esc($department['title']);?></h3>
                                            <?php foreach ($department['groups'] as $key => $title): ?>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="m-gruppe-<?= $key;?>" name="gruppen[]" value="<?= $key;?>"<?= in_array((string) $key, $selectedGroups, true) ? ' checked' : '';?> data-membership-group>
                                                    <label class="form-check-label" for="m-gruppe-<?= $key;?>"><?= esc($title);?></label>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </fieldset>

                            <fieldset class="membership-section">
                                <legend>Beitragsklasse *</legend>
                                <p class="membership-hint">
                                    Bitte wählen Sie genau eine Beitragsklasse.
                                    <?php if ($feeUrl): ?>
                                        Die aktuellen Beiträge finden Sie in der <a href="<?= $feeUrl;?>" target="_blank" rel="noopener">Beitragsübersicht</a>.
                                    <?php endif; ?>
                                </p>
                                <div class="membership-fees<?= $invalid('beitragsklasse');?>">
                                    <?php foreach ($feeClasses as $key => $title): ?>
                                        <div class="form-check">
                                            <input type="radio" class="form-check-input" id="m-beitrag-<?= $key;?>" name="beitragsklasse" value="<?= $key;?>"<?= $selectedFeeClass === $key ? ' checked' : '';?> required>
                                            <label class="form-check-label" for="m-beitrag-<?= $key;?>"><?= esc($title);?></label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </fieldset>

                            <fieldset class="membership-section" id="m-familie" data-membership-family data-max="<?= $maxFamilyMembers;?>">
                                <legend>Weitere Familienmitglieder</legend>
                                <p class="membership-hint">
                                    Beim Familienbeitrag können Sie hier die weiteren Familienmitglieder erfassen, die Mitglied werden möchten (höchstens <?= $maxFamilyMembers;?>).
                                    Der Familienbeitrag gilt für Ehepaare mit und ohne Kind/Kinder bis 18 Jahre/n sowie für Alleinerziehende mit Kind/Kindern bis 18 Jahre/n.
                                </p>
                                <div class="membership-family-list" data-membership-family-list>
                                    <?php foreach ($familyRows as $index => $member): ?>
                                        <?php $familyRow($index, is_array($member) ? $member : []); ?>
                                    <?php endforeach; ?>
                                </div>
                                <button type="button" class="btn btn-outline-primary membership-family-add" data-membership-family-add hidden>
                                    <i class="fa fa-plus" aria-hidden="true"></i> Weiteres Familienmitglied hinzufügen
                                </button>
                                <template id="membership-family-template"><?php $familyRow('__INDEX__', []); ?></template>
                            </fieldset>

                            <fieldset class="membership-section">
                                <legend>Bankverbindung (SEPA-Lastschriftmandat)</legend>
                                <div class="membership-mandate">
                                    <p>
                                        <strong>SEPA-Lastschriftmandat</strong> - Gläubiger-Identifikationsnummer DE 20 ZZZ 000 000 883 64<br/>
                                        Ich ermächtige die Sportfreunde Bronnen e.V., Zahlungen von meinem Konto mittels Lastschrift einzuziehen. Zugleich weise ich mein Kreditinstitut an, die von den Sportfreunden Bronnen e.V. auf mein Konto gezogenen Lastschriften einzulösen.
                                    </p>
                                    <p>
                                        Hinweis: Ich kann innerhalb von acht Wochen, beginnend mit dem Belastungsdatum, die Erstattung des belasteten Betrages verlangen. Es gelten dabei die mit meinem Kreditinstitut vereinbarten Bedingungen.
                                        Der Mitgliedsbeitrag wird durch Lastschriftverfahren am 02. Mai jeden Jahres eingezogen.
                                    </p>
                                    <p class="mb-0"><strong>Mit dem Absenden des Formulars erteilen Sie dieses Mandat.</strong></p>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label for="m-iban">IBAN *</label>
                                            <input type="text" class="form-control flat<?= $invalid('iban');?>" id="m-iban" name="iban" value="<?= $form->old('iban');?>" maxlength="42" autocomplete="off" autocapitalize="characters" spellcheck="false" placeholder="DE00 0000 0000 0000 0000 00" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="m-bic">BIC *</label>
                                            <input type="text" class="form-control flat<?= $invalid('bic');?>" id="m-bic" name="bic" value="<?= $form->old('bic');?>" maxlength="14" autocomplete="off" autocapitalize="characters" spellcheck="false" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="m-kreditinstitut">Kreditinstitut *</label>
                                            <input type="text" class="form-control flat<?= $invalid('kreditinstitut');?>" id="m-kreditinstitut" name="kreditinstitut" value="<?= $form->old('kreditinstitut');?>" maxlength="100" autocomplete="off" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <p class="membership-hint mb-2"><strong>Kontoinhaber</strong></p>
                                        <div class="form-check mb-3" data-membership-account-copy-wrap hidden>
                                            <input type="checkbox" class="form-check-input" id="m-kontoinhaber-gleich" data-membership-account-copy>
                                            <label class="form-check-label" for="m-kontoinhaber-gleich">Der Kontoinhaber ist das Mitglied selbst (Name aus den persönlichen Daten übernehmen)</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="m-kontoinhaber-nachname">Kontoinhaber: Name *</label>
                                            <input type="text" class="form-control flat<?= $invalid('kontoinhaberNachname');?>" id="m-kontoinhaber-nachname" name="kontoinhaberNachname" value="<?= $form->old('kontoinhaberNachname');?>" maxlength="100" autocomplete="off" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="m-kontoinhaber-vorname">Kontoinhaber: Vorname *</label>
                                            <input type="text" class="form-control flat<?= $invalid('kontoinhaberVorname');?>" id="m-kontoinhaber-vorname" name="kontoinhaberVorname" value="<?= $form->old('kontoinhaberVorname');?>" maxlength="100" autocomplete="off" required>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="membership-section">
                                <legend>Datenschutz</legend>
                                <details class="membership-privacy">
                                    <summary>Erklärung zur Erhebung und Verarbeitung personenbezogener Daten nach DSGVO anzeigen</summary>
                                    <div class="membership-privacy__text">
                                        <p>Die in der Beitrittserklärung erhobenen personenbezogenen Daten werden von den Sportfreunden Bronnen 1949 e.V. gemäß Art. 6 Abs. 1 DS-GVO ausschließlich zur Mitgliederverwaltung und zur Erreichung der in der Satzung vorgeschriebenen Vereinsziele verwendet. Für jede darüber hinausgehende Nutzung der personenbezogenen Daten und die Erhebung zusätzlicher Informationen bedarf es regelmäßig der Einwilligung des Betroffenen.</p>
                                        <p>Ihre Daten werden für die Dauer des Fortbestands der Mitgliedschaft von uns gespeichert. Nach Beendigung der Mitgliedschaft werden Ihre personenbezogenen Daten gelöscht.</p>
                                        <p><strong>Rechte des Betroffenen: Auskunft, Berichtigung, Löschung und Sperrung, Widerspruchsrecht</strong></p>
                                        <p>Sie sind gemäß § 34 BDSG jederzeit berechtigt, gegenüber den Sportfreunden Bronnen 1949 e.V. um umfangreiche Auskunftserteilung zu den zu Ihrer Person gespeicherten Daten zu ersuchen. Gemäß § 35 BDSG können Sie jederzeit gegenüber den Sportfreunden Bronnen 1949 e.V. die Berichtigung, Löschung und Sperrung einzelner personenbezogener Daten verlangen.</p>
                                        <p class="mb-0">Sie können darüber hinaus jederzeit ohne Angabe von Gründen von Ihrem Widerspruchsrecht Gebrauch machen und die erteilte Einwilligungserklärung mit Wirkung für die Zukunft abändern oder gänzlich widerrufen. Sie können den Widerruf entweder postalisch oder per E-Mail (<a href="mailto:datenschutz@sf-bronnen.de">datenschutz@sf-bronnen.de</a>) an die Sportfreunde Bronnen 1949 e.V. übermitteln. Es entstehen Ihnen dabei keine anderen Kosten als die Portokosten bzw. die Übermittlungskosten nach den bestehenden Basistarifen.</p>
                                    </div>
                                </details>
                                <div class="form-check membership-consent<?= $invalid('dataDeclaration');?>">
                                    <input type="checkbox" class="form-check-input" id="m-datenschutz" name="dataDeclaration" value="1" required>
                                    <label class="form-check-label" for="m-datenschutz">
                                        * Die Erklärung zur Erhebung und Verarbeitung personenbezogener Daten nach DSGVO sowie die Belehrung meiner Betroffenenrechte habe ich gelesen und zur Kenntnis genommen. Zugleich erteile ich die Einwilligung zur Verwendung meiner personenbezogenen Daten zu den oben angegebenen Zwecken.
                                        Weitere Informationen zum Datenschutz finden Sie auch in der <a href="<?= $privacyUrl;?>" target="_blank" rel="noopener" title="Zur Datenschutzerklärung der Sportfreunde Bronnen">Datenschutzerklärung</a> dieser Webseite.
                                    </label>
                                </div>
                            </fieldset>

                            <p class="membership-hint">
                                * = Pflichtfeld. Aus Ihren Angaben wird die Beitrittserklärung der Sportfreunde Bronnen automatisch ausgefüllt und unserer Mitgliederverwaltung als PDF zugestellt.
                                Die Unterschriften werden dabei aus den eingegebenen Namen erzeugt.
                            </p>

                            <div class="membership-turnstile mb-3">
                                <?php snippet('turnstile', ['action' => 'membership']); ?>
                            </div>

                            <input type="submit" class="btn btn-1 btn-big animation col-12 col-md-4" value="Mitgliedschaft beantragen">

                            <?php echo csrf_field(); ?>
                            <?php echo honeypot_field(); ?>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php snippet('footer', ['scripts' => ['membership']]); ?><?php snippet('close'); ?>
