<?php snippet('header'); ?>
<?php snippet('h1'); ?>
<div class="page">
    <div class="main-container container pl-sm-0 pr-sm-0">
        <?php if ($page->children()->visible()->count() > 0): ?>
            <div class="row blog-post">
                <?php foreach ($page->children()->visible()->sortBy('datum', 'desc') as $post): ?>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="box-3 animation text-xs-center">
                            <p>
                                <?php if ($post->teaserimage()->isNotEmpty()): ?>
                                    <a href="<?= $post->url();?>">
                                        <img src="<?= $site->find($post->uri())->images()->find($post->teaserimage())->url();?>" alt="Blog Image" class="img-fluid img-center-xs">
                                    </a>
                                <?php endif; ?>
                            </p>
                            <div class="inner">
                                <p class="date-meta text-grey-color">
                                    <span class="badge badge-primary">
                                        <i class="fa fa-calendar"></i>&nbsp; <?= date('d.m.Y', strtotime($post->datum()));?>
                                        <i class="fa fa-user ml-3"></i>&nbsp; <?= sprintf('%s %s', site()->user($post->author())->firstName(), site()->user($post->author())->lastName());?>
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
