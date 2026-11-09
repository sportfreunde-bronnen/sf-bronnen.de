<?php snippet('header');?>
<section class="main-banner text-xs-center text-sm-center text-md-start">
    <div class="container text-lite-color ps-sm-0 pe-sm-0">
        <h1 class="text-weight-medium"><?= $page->title() . APPEND_TITLE;; ?></h1>
    </div>
</section>
<div class="page">
    <div class="main-container container ps-sm-0 pe-sm-0 text-xs-center text-sm-center text-md-start">
        <h2 class="main-heading-1 text-spl-color text-weight-normal text-center text-lg-start"><?= $page->headline();?></h2>
        <div class="row">
            <?php foreach ($page->personen()->toStructure() as $person):?>
            <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                <div class="person-card">
                    <span class="person-card__avatar" aria-hidden="true"><i class="fa fa-user"></i></span>
                    <h5 class="person-card__name"><?= $person->name();?></h5>
                    <p class="person-card__role"><b><?= $person->funktion();?></b> <span>(seit <?= $person->start();?>)</span></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php snippet('footer'); ?><?php snippet('close'); ?>
