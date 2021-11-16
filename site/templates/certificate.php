<?php snippet('header'); ?>
<div class="page">
    <section class="main-banner text-xs-center text-sm-center text-md-left">
        <div class="container text-lite-color pl-sm-0 pr-sm-0">
            <h1 class="text-weight-medium"><?= $page->title();?></h1>
        </div>
    </section>
    <div class="main-container container pl-sm-0 pr-sm-0">
        <div class="row text-md-left">
            <div class="col-12">
                <h2 class="main-heading-1 text-spl-color text-uppercase text-weight-normal text-center text-md-left"><?= $page->headline();?></h2>
                <div class="contact-form-wrap">
                    <h5 class="sub-heading-1">Beantragung</h5>
                    <p>
                        <strong>
                            Du brauchst eine Mitgliedschaftsbescheinigung für Deine Krankenkasse oder andere Zwecke? Kein Problem.
                            Fülle einfach das unten stehende Formular aus. Nach der Prüfung durch unsere Mitgliederverwaltung senden wir Dir die Bescheinigung an die angegebene E-Mail Adresse zu.
                        </strong>
                    </p>
                    <hr/>
                    <?php if ($form->success()): ?>
                        <div class="alert alert-success mb-5">
                            Vielen Dank für Deine Anfrage. Wir werden die Bescheinigung so schnell wie möglich ausstellen.
                        </div>
                    <?php else: ?>
                        <?php snippet('uniform/errors', ['form' => $form]); ?>
                    <?php endif; ?>
                    <form id="main-contact-form" class="contact-form" name="contact-form" method="post" role="form">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="address">Name und Anschrift: *</label>
                                    <textarea class="form-control flat" rows="4" name="address" id="address" required="required" placeholder=""><?php echo $form->old('address'); ?></textarea>
                                    <small>Wird für die Empfängeranschrift auf der Bescheinigung verwendet</small>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="email">E-Mail: *</label>
                                    <input type="email" class="form-control flat" value="<?php echo $form->old('email'); ?>" name="email" id="email" required="required" placeholder="Deine E-Mail Adresse">
                                    <small>An welche E-Mail Adresse dürfen wir Dir die Bescheinigung schicken?</small>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <hr/>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="customCheck1" name="dataDeclaration" value="0">
                                    <label class="custom-control-label" for="customCheck1">
                                        <small>
                                            * Ich erkläre mich damit einverstanden, dass die Sportfreunde Bronnen 1949 e.V. meine Angaben zur Ausstellung einer Mitgliedschaftsbescheinigung verwenden.
                                            Eine Weitergabe an Dritte findet grundsätzlich nicht statt, es sei denn geltende Datenschutzvorschriften rechtfertigen eine Übertragung oder die Sportfreunde Bronnen 1949 e.V. sind dazu gesetzlich verpflichtet. Sie können Ihre erteilte Einwilligung jederzeit mit Wirkung für die Zukunft widerrufen. Im Falle des Widerrufs werden Ihre Daten umgehend gelöscht. Ihre Daten werden ansonsten gelöscht, wenn wir Ihre Anfrage bearbeitet haben oder der Zweck der Speicherung entfallen ist. Sie können sich jederzeit über die zu Ihrer Person gespeicherten Daten informieren. Weitere Informationen zum Datenschutz finden Sie auch in der <a href="/home/datenschutz" title="Zur Datenschutzerklärung der Sportfreunde Bronnen">Datenschutzerklärung</a> dieser Webseite.
                                        </small>
                                    </label>
                                </div>
                                <hr/>
                                <small>* = Pflichtfeld</small>
                                <hr/>
                            </div>
                            <div class="col-sm-12">
                                <input type="submit" class="btn btn-1 btn-big animation col-12 col-md-4" value="Bescheinigung beantragen">
                            </div>
                        </div>
                        <?php echo csrf_field(); ?>
                        <?php echo honeypot_field(); ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php snippet('footer'); ?>
<script>
  $(function() {
    $('#address').attr('placeholder', 'Max Mustermann\nMusterweg 12\n88480 Musterhausen');
  });
</script>
<?php snippet('close'); ?>
