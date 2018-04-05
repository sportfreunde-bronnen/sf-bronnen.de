<?php snippet('header'); ?>
<?php snippet('h1'); ?>
<div class="page">
    <div class="main-container container pl-sm-0 pr-sm-0">
        <h2 class="main-heading-1 text-spl-color text-weight-normal text-center-xs"><?= $page->headline();?></h2>
        <?= $page->text()->kirbytext();?>
        <?php snippet('people');?>
    </div>
</div>
<?php snippet('footer'); ?>
