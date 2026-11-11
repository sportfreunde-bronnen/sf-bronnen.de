<?php snippet('header'); ?>
<?php snippet('h1'); ?>
<?php
$hasHeadline = $page->headline()->isNotEmpty();
$hasText = $page->text()->isNotEmpty();
$image = $page->images()->first();
?>
<div class="page page--ah">
    <div class="main-container container ps-sm-0 pe-sm-0">
        <?php if ($hasHeadline || $hasText || $image): ?>
            <article class="content-card">
                <?php if ($hasHeadline): ?>
                    <h2 class="main-heading-1 text-spl-color text-weight-normal text-center text-lg-start"><?= $page->headline();?></h2>
                <?php endif; ?>
                <div class="row">
                    <div class="col-12<?= $image ? ' col-lg-7 mb-4 mb-lg-0' : '';?>">
                        <?php if ($hasText): ?>
                            <div class="content-card__text">
                                <?= $page->text()->kirbytext();?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if ($image): ?>
                        <div class="col-12 col-lg-5">
                            <figure class="content-card__media">
                                <img src="<?= $image->resize(750)->url();?>" class="img-fluid" alt="<?= esc($page->title());?> der Sportfreunde Bronnen" loading="lazy"/>
                                <figcaption><?= esc($page->title());?> der Sportfreunde Bronnen</figcaption>
                            </figure>
                        </div>
                    <?php endif; ?>
                </div>
            </article>
        <?php endif; ?>
        <?php snippet('people');?>
    </div>
</div>
<?php snippet('footer'); ?><?php snippet('close'); ?>
