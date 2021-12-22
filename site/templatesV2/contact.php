<?php snippet('header');?>

<section class="d-flex align-items-center justify-content-center jarallax bg-gradient vh-40 pt-5" data-jarallax="" data-speed="0.25" style="background-image: none;">
    <span class="position-absolute top-0 start-0 w-100 h-100 bg-gradient opacity-80"></span>
    <div class="shape shape-bottom shape-slant bg-body bg-secondary">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 3000 260">
            <polygon fill="currentColor" points="0,257 0,260 3000,260 3000,0"></polygon>
        </svg>
    </div>
    <div class="container position-relative zindex-5 pt-3 pb-7 pt-md-0">
        <div class="row justify-content-center pb-7">
            <div class="col-lg-10 text-center text-lg-start">
                <h1 class="text-light"><?= $page->title();?></h1>
                <p class="text-light"><?= $page->headline();?></p>
            </div>
        </div>
    </div>
</section>

<!-- Contact form + details-->
<section class="container position-relative zindex-5 pb-5">
    <div class="row">
        <div class="col-lg-6 col-md-7 offset-lg-1 pb-2 mb-5" style="margin-top: -150px;">
            <?php if ($form->success()): ?>
                <div class="alert d-flex alert-success" role="alert">
                    <i class="ai-check-circle fs-xl me-3"></i>
                    <div>Vielen Dank für Ihre Anfrage. Wir werden uns umgehend mit Ihnen in Verbindung setzen.</div>
                </div>
            <?php else: ?>
                <?php snippet('uniform/errors', ['form' => $form]); ?>
            <?php endif; ?>
            <div class="card border-0 shadow-lg">
                <form method="POST" class="card-body needs-validation p-5" novalidate>
                    <div class="mb-3 pb-1">
                        <label class="form-label" for="empfaenger">Empfänger<sup class="text-danger ms-1">*</sup></label>
                        <select class="form-control" name="empfaenger" id="empfaenger" required>
                            <?php $i = 0; foreach ($page->ansprechpartner()->toStructure() as $ansprechpartner): $i++; ?>
                                <?php $selected = ($form->old('empfaenger') == $ansprechpartner->email()) ? ' selected ' : ' ';?>
                                <option<?= $selected; ?>value="MagnusBuk@gmx.de"><?= $ansprechpartner->name();?> (<?= $ansprechpartner->aufgabe();?>)</option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">Bitte einen Empfänger angeben</div>
                    </div>
                    <div class="mb-3 pb-1">
                        <label class="form-label" for="vorname">Vorname<sup class="text-danger ms-1">*</sup></label>
                        <input class="form-control" type="text" id="vorname" name="vorname" placeholder="Dein Vorname" required value="<?php echo $form->old('vorname'); ?>">
                        <div class="invalid-feedback">Bitte einen Vornamen angeben</div>
                    </div>
                    <div class="mb-3 pb-1">
                        <label class="form-label" for="nachname">Nachname<sup class="text-danger ms-1">*</sup></label>
                        <input class="form-control" type="text" id="vorname" name="nachname" placeholder="Dein Nachname" required value="<?php echo $form->old('nachname'); ?>">
                        <div class="invalid-feedback">Bitte einen Nachnamen angeben</div>
                    </div>
                    <div class="mb-3 pb-1">
                        <label class="form-label" for="email">E-Mail Adresse<sup class="text-danger ms-1">*</sup></label>
                        <input class="form-control" type="email" id="email" name="email" placeholder="Deine E-Mail Adresse" required value="<?php echo $form->old('email'); ?>">
                        <div class="invalid-feedback">Bitte eine gültige E-Mail Adresse angeben</div>
                    </div>
                    <div class="mb-3 pb-1">
                        <label class="form-label" for="nachricht">Nachricht<sup class="text-danger ms-1">*</sup></label>
                        <textarea class="form-control" id="nachricht" name="nachricht" rows="5" placeholder="Deine Nachricht" required><?php echo $form->old('nachricht'); ?></textarea>
                        <div class="invalid-feedback">Bitte schreibe eine Nachricht</div>
                    </div>
                    <div class="mb-3 pb-1">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="dataDeclaration" name="dataDeclaration" value="0" required>
                            <label class="form-check-label fs-xs" for="dataDeclaration">
                                Ich erkläre mich damit einverstanden, dass die Sportfreunde Bronnen meine Angaben für die Beantwortung meiner Anfrage bzw. Kontaktaufnahme verwenden. Eine Weitergabe an Dritte findet grundsätzlich nicht statt, es sei denn geltende Datenschutzvorschriften rechtfertigen eine Übertragung oder die Sportfreunde Bronnen e.V. sind dazu gesetzlich verpflichtet. Sie können Ihre erteilte Einwilligung jederzeit mit Wirkung für die Zukunft widerrufen. Im Falle des Widerrufs werden Ihre Daten umgehend gelöscht. Ihre Daten werden ansonsten gelöscht, wenn wir Ihre Anfrage bearbeitet haben oder der Zweck der Speicherung entfallen ist. Sie können sich jederzeit über die zu Ihrer Person gespeicherten Daten informieren. Weitere Informationen zum Datenschutz finden Sie auch in der Datenschutzerklärung dieser Webseite.
                            </label>
                            <div class="invalid-feedback">Bitte akzeptiere die Datenschutzbedingungen</div>
                        </div>
                    </div>
                    <div class="text-center pt-2">
                        <button class="btn btn-primary" type="submit">Nachricht abschicken</button>
                    </div>
                    <?php echo csrf_field(); ?>
                    <?php echo honeypot_field(); ?>
                </form>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 offset-lg-1">
            <h2 class="h4 pb-3">Adressen</h2>
            <h3 class="h6 pb-2">Geschäftsadresse</h3>
            <ul class="list-unstyled fs-sm pb-3">
                <li class="d-flex align-items-top mb-3"><i class="ai-map-pin fs-xl text-muted mt-1 me-2 pe-1"></i>
                    <div>Sportfreunde Bronnen e.V.<br/>Adam-Ries-Straße 13<br/>88471 Laupheim</div>
                </li>
                <li class="d-flex align-items-center mb-3"><i class="ai-mail fs-xl text-muted me-2 pe-1"></i>
                    <div>info@sf-bronnen.de</div>
                </li>
            </ul>
            <h3 class="h6 pb-2">Sportanlage & Sportvereinszentrum</h3>
            <ul class="list-unstyled fs-sm">
                <li class="d-flex align-items-top mb-3"><i class="ai-map-pin fs-xl text-muted mt-1 me-2 pe-1"></i>
                    <div>Oberer Espach 3<br/>88480 Achstetten-Bronnen</div>
                </li>
                <li class="d-flex align-items-center mb-3"><i class="ai-mail fs-xl text-muted me-2 pe-1"></i>
                    <div>info@sf-bronnen.de</div>
                </li>
            </ul>
        </div>
    </div>
</section>

<?php snippet('footer');?>
