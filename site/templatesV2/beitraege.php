<?php snippet('header');?>
<section class="my-5">
    <div class="container">
        <h2 class="text-center mb-5"><?= $page->headline();?></h2>
        <p><?= $page->text()->kirbytext();?></p>
        <div class="row">
            <?php foreach ($page->beitraege()->toStructure() as $beitrag): ?>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card w-100 mt-5">
                    <div class="card-img-top bg-gradient text-center py-5 px-grid-gutter">
                        <h3 class="mb-0 text-white"><?= $beitrag->name();?></h3>
                    </div>
                    <div class="">
                        <div class="py-2 bg-secondary w-100 text-center">
                            <span class="fw-normal text-primary mb-1 h3 d-block">
                                <b><?= number_format($beitrag->grundbeitrag()->float(), 2, ',', '.');?> € <?= $beitrag->grundbeitragzusatz();?></b>
                            </span>
                            <span class="d-block">
                                Grundbeitrag
                            </span>
                        </div>
                        <div class="py-2 px-4 w-100 text-center">
                            <span class="fw-normal text-primary mb-1 h3 d-block">
                                <?= number_format($beitrag->fussball()->float(), 2, ',', '.'); ?> € <?= $beitrag->fussballzusatz();?>
                            </span>
                            <span class="d-block">
                                Beitrag Fußball
                            </span>
                        </div>
                        <div class="bg-secondary py-2 px-4 w-100 text-center">
                            <span class="fw-normal text-primary mb-1 h3 d-block">
                                <?= number_format($beitrag->gymnastik()->float(), 2, ',', '.'); ?> € <?= $beitrag->gymnastikzusatz();?>
                            </span>
                            <span class="d-block">
                                Beitrag Gymnastik
                            </span>
                        </div>
                        <div class="py-2 px-4 w-100 text-center">
                            <span class="fw-normal text-primary mb-1 h3 d-block">
                                <?= number_format($beitrag->aikido()->float(), 2, ',', '.'); ?> € <?= $beitrag->aikidozusatz();?>
                            </span>
                            <span class="d-block">
                                Beitrag Aikido
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <p class="mt-5">
            <?= $page->zusatz()->kirbytext();?>
        </p>
    </div>
</section>
<?php snippet('footer');?>
