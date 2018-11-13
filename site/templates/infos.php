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
            <?php snippet('people');?>
        </div>
    </div>
</div>
<?php snippet('footer'); ?><?php snippet('close'); ?>
