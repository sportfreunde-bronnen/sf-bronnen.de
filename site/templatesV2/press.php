<?php snippet('header'); ?>
<section class="my-5">
    <div class="container">
        <h2 class="main-heading-1 text-spl-color text-weight-normal text-center text-lg-start"><?= $page->headline();?></h2>
        <span class="text-center text-lg-start">
            <?= $page->text()->kirbytext();?>
        </span>
        <div class="row mt-5">
            <div class="col-12">
                <!-- Masonry grid with filters -->
                <div class="masonry-filterable">

                    <?php $articles = $page->articles()->sortBy('datum', 'desc')->toStructure();?>
                    <?php if ($articles->count() > 0): ?>
                        <ul class="masonry-filters nav nav-tabs justify-content-center justify-content-lg-start pb-4">
                            <li class="nav-item bg-faded-info my-1">
                                <a class="rounded-0 nav-link active<?= ('all' === $page->getActiveTagGroup()) ? ' active' : '';?>" href="#" data-group="all">Alles</a>
                            </li>
                            <?php foreach ($page->getUsedTags() as $tag): ?>
                                <li class="nav-item bg-faded-info my-1">
                                    <a class="rounded-0 nav-link<?= ($tag === $page->getActiveTagGroup()) ? ' active' : '';?>" href="#" data-group="<?= $tag; ?>"><?= $tag; ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>


                    <div class="masonry-grid" data-columns="4">

                        <?php foreach ($articles as $article): ?>
                        <div class="masonry-grid-item" data-date-created="<?= $article->date()->toDate('Y-m-d');?>" data-groups='<?= $page->getNewsGroupsAsShuffleArray($article);?>'>
                            <div class="card">
                                <div class="card-flip-inner">
                                    <div class="card-body">
                                        <div class="card-body-inner">
                                            <span class="badge bg-secondary ps-0 py-1 my-1">
                                                <i class="ai-calendar"></i>&nbsp; <?= $article->date()->toDate('d.m.Y');?> SZ
                                            </span>
                                            <h3 class="h5 card-title mb-2">
                                                <a target="_blank" href="<?= $article->url();?>"><?= $article->title();?></a>
                                            </h3>
                                            <p class="fs-sm text-muted"><?= substr($article->text(), 0, 100) . '...';?></p>
                                            <span class="btn btn-primary btn-sm text-white">
                                                <a target="_blank" href="<?= $article->url();?>" class="more-link animation text-white">Zum Artikel</a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>


                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php snippet('footer');?>
