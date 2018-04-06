<?php snippet('header'); ?>
<?php snippet('h1'); ?>
<div class="page">
    <div class="main-container container pl-sm-0 pr-sm-0">
        <h2 class="main-heading-1 text-spl-color text-weight-normal text-center-xs"><?= $page->headline();?></h2>
        <div class="row">
            <div class="col-12 col-lg-8">
                <?= $page->text()->kirbytext();?>
            </div>
            <div class="col-12 col-lg-4">
                <?= $page->facts()->kirbytext();?>
            </div>
        </div>
        <hr/>
        <?php snippet('gallery', ['images' => $page->convertFilesToCollection($page->gallery()), 'desc' => 'Bildergalerie']); ?>
        <hr/>
        <section class="section-area section-testimonials">
            <div class="container text-xs-center text-sm-center text-md-left">
                <h3 class="main-heading-1 text-spl-color text-xs-center text-weight-normal">Stimmen</h3>
                <div id="carousel-testimonials" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner text-xs-center text-sm-center text-md-left">
                        <div class="carousel-item active">
                            <div class="row">
                                <?php foreach ($page->quotes()->toStructure() as $quote): ?>
                                <div class="col-md-4 col-sm-12">
                                    <div class="testimonial-box">
                                        <p class="text-weight-light">
                                            <?= $quote->quote();?>
                                        </p>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php if ($page->images()->find('svz_footer.jpg')->isNotEmpty()): ?>
            <div class="text-center">
                <img src="<?= $page->images()->find('svz_footer.jpg')->url(); ?>" class="img-fluid"/>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php snippet('footer'); ?>
