<?php snippet('header'); ?>
    <section class="main-banner text-xs-center text-sm-center text-md-start">
        <div class="container text-lite-color ps-sm-0 pe-sm-0">
            <h1 class="text-weight-medium"><?= $page->title() . APPEND_TITLE;;?></h1>
        </div>
    </section>
    <div class="page">
        <div class="main-container container ps-sm-0 pe-sm-0">
            <?= $page->text()->kirbytext();?>
        </div>
    </div>
<?php snippet('footer'); ?><?php snippet('close'); ?>
