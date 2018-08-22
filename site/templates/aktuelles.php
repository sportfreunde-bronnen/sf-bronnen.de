<?php snippet('header'); ?>
<?php snippet('h1'); ?>
<div class="page">
    <div class="main-container container pl-sm-0 pr-sm-0">
        <?php if ($page->children()->count() > 0): ?>
            <div class="row blog-post">
                <?php foreach ($page->children()->sortBy('datum', 'desc') as $post): ?>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="box-3 animation text-xs-center">
                            <p>
                                <?php if ($post->teaserimage()->isNotEmpty()): ?>
                                    <img src="<?= $site->find($post->uri())->images()->find($post->teaserimage())->url();?>" alt="Blog Image" class="img-fluid img-center-xs">
                                <?php endif; ?>
                            </p>
                            <div class="inner">
                                <p class="date-meta text-grey-color text-uppercase">
                                    <span class="badge badge-secondary">
                                        <i class="fa fa-calendar"></i> <?= date('d.m.Y', strtotime($post->datum()));?>
                                    </span>
                                </p>
                                <h6 class="sub-heading-1 tiny text-medium text-xs-center">
                                    <a href="<?= $post->url();?>"><?= $post->title();?></a>
                                </h6>
                                <p class="text-grey-color">
                                    <?= substr($post->text(), 0, 100) . '...';?>
                                </p>
                                <p class="text-md-right">
                                    <a href="<?= $post->url();?>" class="more-link animation">Weiterlesen</a>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            Aktuell sind keine Berichte vorhanden.
        <?php endif; ?>
    </div>
</div>
<?php snippet('footer'); ?>
