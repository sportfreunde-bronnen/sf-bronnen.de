<?php snippet('header'); ?>
<section class="main-banner text-xs-center text-sm-center text-md-start">
    <div class="container text-lite-color ps-sm-0 pe-sm-0">
        <h1 class="text-weight-medium"><?= $page->title() . APPEND_TITLE;;?></h1>
    </div>
</section>
<div class="page page--sponsors">
    <div class="main-container container ps-sm-0 pe-sm-0">
        <h2 class="main-heading-1 text-spl-color text-weight-normal text-center text-lg-start"><?= $page->headline();?></h2>
        <?= $page->text()->kirbytext();?>
        <div class="row mt-5">
            <?php $box = false; $last = ''; foreach ($page->sponsoren()->toStructure()->sortBy('name', 'asc') as $sponsor): $current = strtoupper(substr($sponsor->name(), 0, 1));?>
                <?php if ($last != $current): ?>
                    <?php if ($box): ?>
                        </ul>
                    </div>
                </div>
                    <?php endif; ?>
                <div class="col-12 col-md-6 col-lg-4 mb-4 supporter-box">
                    <div class="supporter-card">
                        <h3 class="supporter-card__letter"><?= strtoupper($current);?></h3>
                        <ul class="supporter-card__list list-unstyled">
                <?php endif; ?>
                            <li class="supporter-card__item">
                <?php if ($sponsor->webseite()->isNotEmpty()):?>
                                <a href="<?= $sponsor->webseite();?>" title="Zur Webseite: <?= $sponsor->name();?> <?= $sponsor->ort();?>"><?= $sponsor->name();?></a>
                <?php else: ?>
                                <span class="supporter-card__name"><?= $sponsor->name();?></span>
                <?php endif; ?>
                                <small><?= $sponsor->ort();?></small>
                            </li>
            <?php $last = $current; $box = true; endforeach; ?>
            <?php if ($box): ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php snippet('footer'); ?><?php snippet('close'); ?>
