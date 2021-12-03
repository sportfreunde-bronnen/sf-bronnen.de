<?php snippet('header');?>
<section class="my-5">
    <div class="container pt-3 pt-md-0">
        <h2 class="text-center mb-5"><?= $page->headline();?></h2>
        <?php $i = 0; foreach ($page->ereignisse()->toStructure()->sortBy('jahr', 'desc') as $ereignis): $i++; ?>
            <div class="border-top py-4<?= $i % 2 != 0 ? ' bg-secondary' : '';?>">
                <div class="row py-md-2">
                    <div class="d-flex col-lg-2 col-sm-3">
                        <div class="h5 text-body mb-0 px-3 py-3">Jahr <?= $ereignis->jahr();?></div>
                    </div>
                    <div class="col-sm-9 offset-lg-1">
                        <div class="text-body mb-0 py-3">
                            <?= $ereignis->ereignis()->kirbytext();?>
                        </div>
                        <?php if ($ereignis->images()->toFiles()->count() > 0): ?>
                        <div class="row gallery">
                            <?php foreach ($ereignis->images()->toFiles() as $image): ?>
                            <div class="col-6 col-md-4 col-lg-3 mb-grid-gutter">
                                <a href="<?= $image->url();?>" class="gallery-item" data-sub-html='<h6 class="fs-sm text-light"><?= $image->description();?></h6>'>
                                    <img src="<?= $image->resize(300)->url();?>" alt="Gallery thumbnail">
                                    <span class="gallery-caption"><?= $image->description();?></span>
                                </a>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
</section>
<?php snippet('footer');?>