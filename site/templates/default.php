<?php snippet('header'); ?>
<section class="main-banner text-xs-center text-sm-center text-md-left">
    <div class="container text-lite-color pl-sm-0 pr-sm-0">
        <h1 class="text-weight-medium"><?= $page->title() . APPEND_TITLE;;?></h1>
    </div>
</section>
<div class="page">
<div class="main-container container pl-sm-0 pr-sm-0">
    <h2 class="main-heading-1 text-spl-color text-weight-normal text-center text-lg-left"><?= $page->headline();?></h2>
    <?= $page->text()->kirbytext();?>
    <hr/>
    <?php if ($page->images()): ?>
        <div class="row">
            <?php foreach($page->images() as $image): ?>
                <div class="col-12 col-md-6 col-lg-4 col-xl-3 text-center mb-3">
                    <figure class="figure">
                        <img src="<?= $image->resize(750)->url();;?>" class="figure-img img-fluid">
                        <figcaption class="figure-caption"><?= $image->caption();?></figcaption>
                    </figure>
                    <hr class="d-md-none"/>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <?php if ($page->people()->isNotEmpty()): ?>
        <?php snippet('people');?>
    <?php endif; ?>
</div>
</div>
<?php snippet('footer'); ?><?php snippet('close'); ?>
