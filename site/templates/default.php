<?php snippet('header'); ?>
<section class="main-banner text-xs-center text-sm-center text-md-left">
    <div class="container text-lite-color pl-sm-0 pr-sm-0">
        <h1 class="text-weight-medium"><?= $page->title();?></h1>
    </div>
</section>
<div class="page">
<div class="main-container container pl-sm-0 pr-sm-0">
    <?= $page->text()->kirbytext();?>
    <hr/>
    <?php if ($page->images()): ?>
        <div class="text-center">
            <?php foreach($page->images() as $image): ?>
                <img src="<?= $image->resize(750)->url();?>" class="img-fluid"/>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
</div>
<?php snippet('footer');?>
