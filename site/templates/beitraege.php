<?php snippet('header'); ?>
<section class="main-banner text-xs-center text-sm-center text-md-left">
    <div class="container text-lite-color pl-sm-0 pr-sm-0">
        <h1 class="text-weight-medium"><?= $page->title(); ?></h1>
    </div>
</section>
<div class="page">
    <div class="main-container container pl-sm-0 pr-sm-0">
        <h2 class="main-heading-1 text-spl-color text-weight-normal text-uppercase text-center text-md-left"><?= $page->headline();?></h2>
        <p><?= $page->text()->kirbytext();?></p>
        <div class="row">
            <?php foreach ($page->beitraege()->toStructure() as $beitrag): ?>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="pricing-table text-center animation">
                        <div class="plan-name">
                            <h5 class="text-weight-medium"><?= $beitrag->name();?></h5>
                        </div>
                        <div class="plan-price">
                            <h3><?= number_format($beitrag->grundbeitrag()->float(), 2, ',', '.');?> € <?= $beitrag->grundbeitragzusatz();?></h3>
                            <span class="h5 text-weight-thin">Grundbeitrag</span>
                        </div>
                        <div class="plan-list">
                            <ul class="list-unstyled">
                                <li class="animation">
                                    <h5><?= number_format($beitrag->fussball()->float(), 2, ',', '.'); ?> € <?= $beitrag->fussballzusatz();?></h5>
                                    Beitrag Fußball
                                </li>
                                <li class="animation">
                                    <h5><?= number_format($beitrag->gymnastik()->float(), 2, ',', '.'); ?> € <?= $beitrag->gymnastikzusatz();?></h5>
                                    Beitrag Gymnastik
                                </li>
                                <li class="animation">
                                    <h5><?= number_format($beitrag->aikido()->float(), 2, ',', '.'); ?> € <?= $beitrag->aikidozusatz();?></h5>
                                    Beitrag Aikido
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?= $page->zusatz()->kirbytext();?>
    </div>
</div>
<?php snippet('footer'); ?><?php snippet('close'); ?>