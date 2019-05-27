<?php snippet('header'); ?>
<section class="main-banner text-xs-center text-sm-center text-md-left">
    <div class="container text-lite-color pl-sm-0 pr-sm-0">
        <h1 class="text-weight-medium"><?= $page->title();?></h1>
    </div>
</section>
<div class="page">
    <div class="main-container container pl-sm-0 pr-sm-0">
        <div class="row">
            <div class="col-12">
                <h2 class="main-heading-1 text-spl-color text-weight-normal text-center-xs"><?= $page->headline();?></h2>
                <?= $page->text()->kirbytext();?>
                <hr/>
                <?php if ($page->images()): ?>
                    <div class="text-center">
                        <?php foreach($page->images() as $image): ?>
                            <img src="<?= $image->resize(750)->url();?>" class="img-fluid"/>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <?php if ($page->people()->isNotEmpty()): ?>
                    <?php snippet('people');?>
                <?php endif; ?>
            </div>
            <?php if (2==2): ?>
            <div class="col-12">
                <h3 class="main-heading-1 text-spl-color text-weight-normal text-center-xs mt-2">Anmeldung</h3>
                <?php if(isset($success)): ?>
                    <div class="alert alert-success">
                        <?= $success; ?>
                    </div>
                    <div class="alert alert-info">
                        Zum finalen Abschluss der Anmeldung überweist bitte die Startgebühr (20€) auf das folgende Konto:<br/>
                        <b>Empfänger</b>: Zwickel-Elfer-Cup<br/>
                        <b>IBAN</b>: DE73 6549 1320 0009 2500 18<br/>
                        <b>BIC</b>: GENODES1VBL<br/>
                        <b>Kreditinstitut</b>: VR-Bank Laupheim-Illertal eG<br/>
                        <b>Verwendungszweck</b>: Euren „TEAM-NAMEN“ bitte unbedingt angeben !!
                    </div>
                <hr/>
                <?php endif; ?>
                <?php if($alert): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach($alert as $message): ?>
                                <li><?= html($message) ?></li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                <?php endif ?>
                <form id="main-contact-form" class="contact-form" name="contact-form" method="post" role="form">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="fname">Name eurer Mannschaft: *</label>
                                <input type="text" class="form-control flat" value="<?= isset($data['name']) ? esc($data['name']) : '' ?>" name="name" id="name" required="required" placeholder="Wie heißt eure Mannschaft?">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="lname">Ortschaft *</label>
                                <input type="text" class="form-control flat" value="<?= isset($data['ort']) ? esc($data['ort']) : '' ?>" name="ort" id="ort" placeholder="Woher seid ihr?">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="lname">Ansprechpartner: *</label>
                                <input type="text" class="form-control flat" value="<?= isset($data['contact']) ? esc($data['contact']) : '' ?>" name="contact" id="contact" placeholder="Wer ist euer Ansprechpartner?">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="email">E-Mail: *</label>
                                <input type="email" class="form-control flat" value="<?= isset($data['email']) ? esc($data['email']) : '' ?>" name="email" id="email" required="required" placeholder="E-Mail Adresse">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <hr/>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="dataDeclarationCheck" name="dataDeclaration" value="0">
                                <label class="custom-control-label" for="dataDeclarationCheck">
                                    <small>
                                        * Ich erkläre mich damit einverstanden, dass die Sportfreunde Bronnen 1949 e.V. meine Angaben für die Durchführung des Zwickel-Elfercups 2019 verwenden.
                                        Eine Weitergabe an Dritte findet grundsätzlich nicht statt, es sei denn geltende Datenschutzvorschriften rechtfertigen eine Übertragung oder die Sportfreunde Bronnen 1949 e.V. sind dazu gesetzlich verpflichtet. Sie können Ihre erteilte Einwilligung jederzeit mit Wirkung für die Zukunft widerrufen. Im Falle des Widerrufs werden Ihre Daten umgehend gelöscht. Ihre Daten werden ansonsten gelöscht, wenn wir Ihre Anfrage bearbeitet haben oder der Zweck der Speicherung entfallen ist. Sie können sich jederzeit über die zu Ihrer Person gespeicherten Daten informieren. Weitere Informationen zum Datenschutz finden Sie auch in der <a href="/home/datenschutz" title="Zur Datenschutzerklärung der Sportfreunde Bronnen">Datenschutzerklärung</a> dieser Webseite.
                                    </small>
                                </label>
                            </div>
                            <hr/>
                            <small>* = Pflichtfeld</small>
                            <hr/>
                        </div>
                        <div class="col-sm-12">
                            <input type="submit" class="btn btn-1 btn-big animation col-12 col-md-4" value="Mannschaft anmelden">
                        </div>
                    </div>
                    <div class="honey">
                        <label for="message">If you are a human, leave this field empty</label>
                        <input type="website" name="website" id="website" placeholder="http://example.com" value="<?= isset($data['website']) ? esc($data['website']) : '' ?>"/>
                    </div>
                </form>
            </div>
            <?php endif; ?>
            <div class="col-12">
                <h3 class="main-heading-1 text-spl-color text-weight-normal text-center-xs mt-5">Bestätigte Mannschaften <?= date('Y');?></h3>
                <ul class="mb-0">
                <?php $i = 0; foreach ($page->teams()->toStructure()->filterBy('year', date('Y')) as $team): $i++; ?>
                    <li><b><?= $team->name();?></b> - (Startgebühr: <?= $team->payed() === 1 ? 'Bezahlt' : 'Offen';?>)</li>
                <?php endforeach; ?>
                <?php if (0 === $i): ?>
                    <li>Noch keine Daten vorhanden</li>
                <?php endif;?>
                </ul>
        </div>
    </div>
</div>
<?php snippet('footer'); ?><?php snippet('close'); ?>
