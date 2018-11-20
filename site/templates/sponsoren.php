<?php snippet('header'); ?>
<section class="main-banner text-xs-center text-sm-center text-md-left">
    <div class="container text-lite-color pl-sm-0 pr-sm-0">
        <h1 class="text-weight-medium"><?= $page->title();?></h1>
    </div>
</section>
<div class="page page--sponsors">
    <div class="main-container container pl-sm-0 pr-sm-0">
        <h2 class="main-heading-1 text-spl-color text-weight-normal text-center-xs"><?= $page->headline();?></h2>
        <?= $page->text()->kirbytext();?>
        <div class="row mt-5">
            <?php $box = false; $last = ''; foreach ($page->sponsoren()->toStructure()->sortBy('name', 'asc') as $sponsor): $current = strtoupper(substr($sponsor->name(), 0, 1)); ?>
                <?php if ($last != $current): ?>
                    <?php if ($box): ?>
                    </div>
                    <?php endif; ?>
                    <div class="col-12 col-md-6 col-lg-4 mb-4 mb-md-4 sponsor-box">
                    <h3 class="main-heading-1 text-spl-color text-weight-normal text-center-xs mb-3 mb-md-5"><?= strtoupper($current);?></h3>
                <?php endif; ?>
                <?php if ($sponsor->webseite()->isNotEmpty()):?>
                    <a href="<?= $sponsor->webseite();?>" alt="Zur Webseite: <?= $sponsor->name();?> <?= $sponsor->ort();?>" title="Zur Webseite: <?= $sponsor->name();?> <?= $sponsor->ort();?>"><?= $sponsor->name();?></a><br/><small><i><?= $sponsor->ort();?></i></small><br/>
                <?php else: ?>
                    <?= $sponsor->name();?><br/><small><i><?= $sponsor->ort();?></i></small><br/>
                <?php endif; ?>
            <?php $last = $current; $box = true; endforeach; ?>
        </div>
    </div>
</div>
<?php snippet('footer'); ?><?php snippet('close'); ?>
