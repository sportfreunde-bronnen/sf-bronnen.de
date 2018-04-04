<?php snippet('header'); ?>
    <section class="main-banner text-xs-center text-sm-center text-md-left">
        <div class="container text-lite-color pl-sm-0 pr-sm-0">
            <h1 class="text-weight-medium"><?= $page->title();?></h1>
        </div>
    </section>
    <div class="page">
        <div class="main-container container pl-sm-0 pr-sm-0">
            <?= $page->text()->kirbytext();?>
        </div>
    </div>
<?php snippet('footer');?>
