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
                <h2 class="main-heading-1 text-spl-color text-uppercase text-weight-normal"><?= $page->headline();?></h2>
                <div class="contact-form-wrap">
                    <h5 class="sub-heading-1">Kontaktformular</h5>
                    <div class="status alert alert-success contact-status"></div>
                    <form id="main-contact-form" class="contact-form" name="contact-form" method="post" action="sendemail.php" role="form">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="selectEmpfaenger">Empfänger: *</label>
                                    <select class="form-control flat no-border-radius" id="selectEmpfaenger">
                                        <?php $i = 0; foreach ($page->ansprechpartner()->toStructure() as $ansprechpartner): $i++; ?>
                                            <option value="<?= $i;?>"><?= $ansprechpartner->name();?> (<?= $ansprechpartner->aufgabe();?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="fname">Vorname: * </label>
                                    <input type="text" class="form-control flat" name="fname" id="fname" required="required" placeholder="Ihr Vorname">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="lname">Nachname: </label>
                                    <input type="text" class="form-control flat" name="lname" id="lname" placeholder="Ihr Nachname">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="email">E-Mail: *</label>
                                    <input type="email" class="form-control flat" name="email" id="email" required="required" placeholder="Ihre E-Mail Adresse">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="message">Ihre Nachricht: *</label>
                                    <textarea class="form-control flat" rows="8" name="message" id="message" required="required" placeholder="Ihre Nachricht"></textarea>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <p>
                                    <small>* = Pflichtfeld</small>
                                    <hr/>
                                    <small>
                                        Wenn Sie die im Kontaktformular eingegebenen Daten durch Klick auf den nachfolgenden Button übersenden, erklären Sie sich damit einverstanden, dass wir Ihre Angaben für die Beantwortung Ihrer Anfrage bzw. Kontaktaufnahme verwenden. Eine Weitergabe an Dritte findet grundsätzlich nicht statt, es sei denn geltende Datenschutzvorschriften rechtfertigen eine Übertragung oder wir dazu gesetzlich verpflichtet sind. Sie können Ihre erteilte Einwilligung jederzeit mit Wirkung für die Zukunft widerrufen. Im Falle des Widerrufs werden Ihre Daten umgehend gelöscht. Ihre Daten werden ansonsten gelöscht, wenn wir Ihre Anfrage bearbeitet haben oder der Zweck der Speicherung entfallen ist. Sie können sich jederzeit über die zu Ihrer Person gespeicherten Daten informieren. Weitere Informationen zum Datenschutz finden Sie auch in der <a href="/home/datenschutz" title="Zur Datenschutzerklärung der Sportfreunde Bronnen">Datenschutzerklärung</a> dieser Webseite.
                                    </small>
                                </p>
                            </div>
                            <div class="col-sm-12">
                                <input type="submit" class="btn btn-1 btn-big animation col-12 col-md-4" value="Nachricht abschicken">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php snippet('footer'); ?>