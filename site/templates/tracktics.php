<?php snippet('header'); ?>
<section class="main-banner text-xs-center text-sm-center text-md-left">
    <div class="container text-lite-color pl-sm-0 pr-sm-0">
        <h1 class="text-weight-medium">Tracktics Stats</h1>
    </div>
</section>
<div class="page">
    <div class="main-container container pl-sm-0 pr-sm-0">
        <h2 class="main-heading-1 text-spl-color text-weight-normal text-center text-lg-left"><?= $page->spieler();?> vs. <?= $page->gegner();?> (<?= date('d.m.Y', $page->date());?>)</h2>
        <hr/>
        <?= $page->parent()->text()->kirbytext();?>
        <hr/>
        <?php if ($page->images()): ?>
            <div class="text-center">
                <?php foreach($page->images() as $image): ?>
                    <img src="<?= $image->resize(750)->url();?>" class="img-fluid mb-2"/>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php snippet('footer'); ?><?php snippet('close'); ?>
