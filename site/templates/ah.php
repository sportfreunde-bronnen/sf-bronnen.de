<?php snippet('header'); ?>
<?php snippet('h1'); ?>
<div class="page">
    <div class="main-container container pl-sm-0 pr-sm-0">
        <h2 class="main-heading-1 text-spl-color text-weight-normal text-center text-lg-left"><?= $page->headline();?></h2>
        <?php if ($page->images()->count() > 0): ?>
            <div class="row">
                <div class="col-12 col-lg-7">
                    <?= $page->text()->kirbytext();?>
                </div>
                <div class="col-12 col-lg-5 text-center">
                    <figure>
                        <img src="<?= $page->images()->first()->resize(750)->url();?>" class="img-fluid"/>
                        <figcaption><small><?= $page->title(); ?> der Sportfreunde Bronnen</small></figcaption>
                    </figure>
                </div>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-12">
                    <?= $page->text()->kirbytext();?>
                </div>
            </div>
        <?php endif; ?>
        <?php snippet('people');?>
    </div>
</div>
<?php snippet('footer'); ?><?php snippet('close'); ?>
