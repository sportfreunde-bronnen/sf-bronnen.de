<?php snippet('header'); ?>
<?php
$ereignisse = $page->ereignisse()->toStructure()->sortBy('jahr', 'desc');
$anzahl = $ereignisse->count();
$position = 0;
?>
<div class="page page--chronik">
    <section class="main-banner text-xs-center text-sm-center text-md-start">
        <div class="container text-lite-color ps-sm-0 pe-sm-0">
            <h1 class="text-weight-medium"><?= $page->title() . APPEND_TITLE;; ?></h1>
        </div>
    </section>
    <div class="main-container container ps-sm-0 pe-sm-0 text-xs-center text-sm-center text-md-start">
        <h2 class="main-heading-1 text-spl-color text-weight-normal text-center text-lg-start"><?= $page->headline();?></h2>
        <p class="timeline-hint">
            <i class="fa fa-comment-o" aria-hidden="true"></i>
            <span>Es fehlt etwas? <a href="/home/kontakt">Lass es uns wissen!</a></span>
        </p>
        <?php if ($anzahl > 0): ?>
        <ol class="timeline" aria-label="Zeitstrahl der Vereinsgeschichte">
            <?php foreach ($ereignisse as $ereignis): ?>
                <?php $position++; ?>
                <li class="timeline__item<?= $position === $anzahl ? ' timeline__item--start' : '' ?>">
                    <span class="timeline__marker" aria-hidden="true"></span>
                    <article class="timeline__card">
                        <h3 class="timeline__year"><?= $ereignis->jahr()->html();?></h3>
                        <div class="timeline__content">
                            <?= $ereignis->ereignis()->kirbytext();?>
                        </div>
                    </article>
                </li>
            <?php endforeach; ?>
        </ol>
        <?php endif; ?>
    </div>
</div>
<?php snippet('footer'); ?><?php snippet('close'); ?>
