<?php snippet('header'); ?>
<div class="page--infos">
    <section class="main-banner text-xs-center text-sm-center text-md-left">
        <div class="container text-lite-color pl-sm-0 pr-sm-0">
            <h1 class="text-weight-medium">Abteilung <?= $page->parents()->last()->title() . APPEND_TITLE;;?></h1>
        </div>
    </section>
    <div class="page">
        <div class="main-container container pl-sm-0 pr-sm-0 text-xs-center text-sm-center text-md-left">
            <h2 class="main-heading-1 text-spl-color text-weight-normal text-center text-lg-left"><?= $page->headline();?></h2>
            <?= nl2br($page->infotext()->kirbytext());?>
            <?php if ($page->images()->count() > 0): ?>
              <div class="col-12 text-center-md">
                  <?php foreach ($page->images()->limit(2) as $image): ?>
                    <figure>
                      <img class="rounded mb-1 img-fluid" src="<?= $image->resize(1000)->url();?>"/>
                      <?php if ($image->description()->isNotEmpty()):?>
                      <figcaption><?= $image->description();?></figcaption>
                      <?php endif; ?>
                    </figure>
                  <?php endforeach; ?>
              </div>
            <?php endif; ?>
            <hr/>
            <?php snippet('people');?>
        </div>
    </div>
</div>
<?php snippet('footer'); ?><?php snippet('close'); ?>
