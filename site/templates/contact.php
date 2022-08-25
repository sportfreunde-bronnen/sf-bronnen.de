<?php snippet('header'); ?>
<div class="page">
    <section class="main-banner text-xs-center text-sm-center text-md-left">
        <div class="container text-lite-color pl-sm-0 pr-sm-0">
            <h1 class="text-weight-medium"><?= $page->title() . APPEND_TITLE;;?></h1>
        </div>
    </section>
    <div class="main-container container pl-sm-0 pr-sm-0">
        <div class="row text-md-left">
            <div class="col-12">
                <h2 class="main-heading-1 text-spl-color text-uppercase text-weight-normal text-center text-md-left"><?= $page->headline();?></h2>
                <div class="contact-form-wrap">
                    <h5 class="sub-heading-1">Kontaktformular</h5>
                    <?php if ($form->success()): ?>
                        <div class="alert alert-success mb-5">
                            Vielen Dank für Ihre Anfrage. Wir werden uns umgehend mit Ihnen in Verbindung setzen.
                        </div>
                    <?php else: ?>
                        <?php snippet('uniform/errors', ['form' => $form]); ?>
                    <?php endif; ?>
                    <form id="main-contact-form" class="contact-form" name="contact-form" method="post" role="form">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="selectEmpfaenger">Empfänger: *</label>
                                    <select class="form-control flat no-border-radius" id="selectEmpfaenger" name="empfaenger">
                                        <?php $i = 0; foreach ($page->ansprechpartner()->toStructure() as $ansprechpartner): $i++; ?>
                                            <?php $selected = ($form->old('empfaenger') == $ansprechpartner->email()) ? ' selected ' : ' ';?>
                                            <option<?= $selected; ?>value="<?= $ansprechpartner->email();?>"><?= $ansprechpartner->name();?> (<?= $ansprechpartner->aufgabe();?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="fname">Vorname: * </label>
                                    <input type="text" class="form-control flat" value="<?php echo $form->old('vorname'); ?>" name="vorname" id="vorname" required="required" placeholder="Ihr Vorname">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="lname">Nachname: </label>
                                    <input type="text" class="form-control flat" value="<?php echo $form->old('nachname'); ?>" name="nachname" id="nachname" placeholder="Ihr Nachname">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="email">E-Mail: *</label>
                                    <input type="email" class="form-control flat" value="<?php echo $form->old('email'); ?>" name="email" id="email" required="required" placeholder="Ihre E-Mail Adresse">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="message">Ihre Nachricht: *</label>
                                    <textarea class="form-control flat" rows="8" name="nachricht" id="nachricht" required="required" placeholder="Ihre Nachricht"><?php echo $form->old('nachricht'); ?></textarea>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <hr/>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="customCheck1" name="dataDeclaration" value="0">
                                    <label class="custom-control-label" for="customCheck1">
                                        <small>
                                            * Ich erkläre mich damit einverstanden, dass die Sportfreunde Bronnen 1949 e.V. meine Angaben für die Beantwortung meiner Anfrage bzw. Kontaktaufnahme verwenden.
                                            Eine Weitergabe an Dritte findet grundsätzlich nicht statt, es sei denn geltende Datenschutzvorschriften rechtfertigen eine Übertragung oder die Sportfreunde Bronnen 1949 e.V. sind dazu gesetzlich verpflichtet. Sie können Ihre erteilte Einwilligung jederzeit mit Wirkung für die Zukunft widerrufen. Im Falle des Widerrufs werden Ihre Daten umgehend gelöscht. Ihre Daten werden ansonsten gelöscht, wenn wir Ihre Anfrage bearbeitet haben oder der Zweck der Speicherung entfallen ist. Sie können sich jederzeit über die zu Ihrer Person gespeicherten Daten informieren. Weitere Informationen zum Datenschutz finden Sie auch in der <a href="/home/datenschutz" title="Zur Datenschutzerklärung der Sportfreunde Bronnen">Datenschutzerklärung</a> dieser Webseite.
                                        </small>
                                    </label>
                                </div>
                                <hr/>
                                <small>* = Pflichtfeld</small>
                                <hr/>
                            </div>
                            <div class="col-sm-12">
                                <input type="submit" class="btn btn-1 btn-big animation col-12 col-md-4" value="Nachricht abschicken">
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
<?php snippet('footer'); ?><?php snippet('close'); ?>