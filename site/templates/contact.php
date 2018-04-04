<?php snippet('header'); ?>
<div class="page">
    <section class="main-banner text-xs-center text-sm-center text-md-left">
        <div class="container text-lite-color pl-sm-0 pr-sm-0">
            <h1 class="text-weight-medium"><?= $page->title();?></h1>
        </div>
    </section>
    <div class="main-container container pl-sm-0 pr-sm-0">
        <div class="row text-xs-center text-sm-center text-md-left">
            <div class="col-12">
                <h2 class="main-heading-1 text-spl-color text-uppercase text-weight-normal">Treten Sie mit uns in Kontakt</h2>
                <div class="contact-form-wrap">
                    <h5 class="sub-heading-1 text-xs-center text-sm-center text-md-left">Kontaktformular</h5>
                    <div class="status alert alert-success contact-status"></div>
                    <form id="main-contact-form" class="contact-form" name="contact-form" method="post" action="sendemail.php" role="form">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="selectEmpfaenger">Empfänger</label>
                                    <select class="form-control flat no-border-radius" id="selectEmpfaenger">
                                        <?php $i = 0; foreach ($page->ansprechpartner()->toStructure() as $ansprechpartner): $i++; ?>
                                            <option value="<?= $i;?>"><?= $ansprechpartner->name();?> (<?= $ansprechpartner->aufgabe();?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="fname" class="sr-only">Vorname: </label>
                                    <input type="text" class="form-control flat" name="fname" id="fname" required="required" placeholder="Ihr Vorname">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="lname" class="sr-only">Nachname: </label>
                                    <input type="text" class="form-control flat" name="lname" id="lname" required="required" placeholder="Ihr Nachname">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="email" class="sr-only">E-Mail: </label>
                                    <input type="text" class="form-control flat" name="email" id="email" required="required" placeholder="Ihre E-Mail Adresse">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="message" class="sr-only">Ihre Nachricht: </label>
                                    <textarea class="form-control flat" rows="8" name="message" id="message" required="required" placeholder="Ihre Nachricht"></textarea>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <input type="submit" class="btn btn-1 btn-big animation" value="Nachricht abschicken">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php snippet('footer'); ?>