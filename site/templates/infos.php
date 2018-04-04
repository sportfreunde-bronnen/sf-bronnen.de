<?php snippet('header'); ?>
<div class="page--chronik">
    <section class="main-banner text-xs-center text-sm-center text-md-left">
        <div class="container text-lite-color pl-sm-0 pr-sm-0">
            <h1 class="text-weight-medium">Abteilung <?= $page->parents()->last()->title();?></h1>
        </div>
    </section>
    <div class="page">
        <div class="main-container container pl-sm-0 pr-sm-0 text-xs-center text-sm-center text-md-left">
            <h2 class="main-heading-1 text-spl-color text-weight-normal text-center-xs"><?= $page->headline();?></h2>
            <?= nl2br($page->infotext()->kirbytext());?>
            <hr/>
            <h3 class="main-heading-1 text-spl-color text-weight-normal text-center-xs mt-5">Verantwortliche</h3>
            <div class="row">
                <div class="col-12">
                    <div class="bio-box-1 two-col row">
                        <?php foreach ($page->people()->toStructure() as $person): ?>
                            <div class="col-6 mb-5">
                                <h5 class="text-weight-normal"><?= $person->name();?></h5>
                                <p class="designation text-weight-light text-grey-color"><?= $person->aufgabe();?></p>
                                <?php if (!$person->email()->isEmpty()): ?>
                                    <p class="text-weight-medium mt-2">E-Mail: <span class="text-weight-light text-grey-color"><a href="mailto:<?= $person->email();?>"><?= $person->email();?></a></span></p>
                                <?php endif; ?>
                                <?php if (!$person->phone()->isEmpty()): ?>
                                    <p class="text-weight-medium mt-2">Telefon: <span class="text-weight-light text-grey-color"><a href="mailto:<?= $person->phone();?>"><?= $person->phone();?></a></span></p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php snippet('footer'); ?>
