<?php snippet('header');?>

<div class="sidebar-enabled">
    <div class="container">
        <div class="row">
            <!-- Sidebar-->
            <div class="sidebar col-lg-3 pt-lg-5">
                <div class="offcanvas offcanvas-collapse" id="blog-sidebar">
                    <div class="offcanvas-header navbar-shadow px-4 mb-3">
                        <h5 class="mt-1 mb-0">Navigation</h5>
                        <button class="btn-close lead" type="button" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body px-4 pt-3 pt-lg-0 ps-lg-0 pe-lg-2 pe-xl-4" data-simplebar>
                        <!-- Categories-->
                        <div class="widget widget-categories mb-5">
                            <h3 class="widget-title">Abteilungen</h3>
                            <ul>
                                <?php foreach(site()->index()->children()->listed()->filterBy('template', 'aktuelles') as $category): ?>
                                <li><a class="widget-link<?= $category->isOpen() ? ' active' : '';?>" href="<?= $category->url();?>"><?= $category->parent()->parent()->title();?><small class="text-muted ps-1 ms-2"><?= $category->children()->listed()->filterBy('template', 'bericht')->count();?></small></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <?php if ($page->getTrendingArticles()->count() > 0): ?>
                        <!-- Featured posts-->
                        <div class="widget mt-n1 mb-5">
                            <h3 class="widget-title pb-1">Aus dem Verein</h3>
                            <?php $i = 0; foreach ($page->getTrendingArticles()->slice(0, 1000) as $post): $i++; ?>
                                <div class="d-flex align-items-center pb-1 mb-3">
                                    <a class="d-block flex-shrink-0" href="<?= $post->url();?>">
                                        <img class="rounded" src="<?= $post->teaserimage()->toFile()->crop(64, 64, 'center')->url(); ?>" alt="<?= $post->title();?>" width="64">
                                    </a>
                                    <div class="ps-2 ms-1">
                                        <h4 class="fs-md nav-heading mb-1"><a class="fw-medium" href="<?= $post->url();?>"><?= $post->title();?></a></h4>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>

                        <!-- Tag cloud-->
                        <div class="widget mb-5">
                            <h3 class="widget-title pb-1">Tags</h3>
                            <?php foreach ($page->getUsedTags() as $tag): ?>
                                <a class="btn-tag me-2 mb-2<?= get('tag') == trim($tag) ? ' bg-primary text-white' : '';?>" href="<?= $page->url();?>?tag=<?= trim($tag);?>">#<?= trim($tag);?></a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Content-->
            <div class="col-lg-9 content py-5 mb-2 mb-sm-0 pb-sm-5">

                <h1 class="mb-5"><?= $page->title();?> - <?= $page->parent()->parent()->title();?></h1>

                <?php if(get('tag', null) !== null): ?>
                <div class="d-flex align-items-center justify-content-between border-top border-bottom mb-5">
                    <div class="d-flex align-items-center py-1 text-center text-md-start">
                        <a class="d-flex btn-tag me-2 my-2 bg-primary text-white" href="">#<?= trim(get('tag'));?></a>
                    </div>
                    <div class="d-flex fs-6 ml-5 d-flex align-items-center">
                        <a href="<?= $page->url();?>">Filter zurücksetzen</a>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Post-->
                <?php $i = 0; foreach ($articles = $page->getArticles()->paginate(10) as $post): $i++; ?>
                <article class="card card-horizontal card-hover mb-grid-gutter">
                    <a class="card-img-top<?= $i % 2 == 0 ? ' order-sm-2' : ' order-sm-1';?>" href="<?= $post->url();?>" style="background-image: url(<?= $post->teaserimage()->toFile()->crop(510, 340, 'top left')->url(); ?>);"></a>
                    <div class="card-body<?= $i % 2 == 0 ? ' order-sm-1' : ' order-sm-2';?>">
                        <span class="meta-link fs-sm mb-2">
                            <i class="ai-user"></i> <?= $post->author()->toUser()?->alias() ?? 'Autor';?></span>
                        <h2 class="h4 nav-heading mb-4">
                            <a href="<?= $post->url();?>"><?= $post->title();?></a>
                        </h2>
                        <div class="mt-3 text-start text-nowrap">
                            <a class="meta-link fs-xs" href="#">
                                <i class="ai-calendar me-1 mt-n1"></i>&nbsp;<?= $post->datum()->toDate('d.m.Y');?>
                            </a>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>

                <!-- Pagination-->
                <div class="d-md-flex justify-content-center align-items-center pt-3 pb-2">
                    <?php $pagination = $articles->pagination() ?>
                    <nav>
                        <ul class="pagination justify-content-center">
                            <?php if ($pagination->hasPrevPage()): ?>
                                <li class="page-item"><a class="page-link" href="<?= $pagination->prevPageURL() ?>" aria-label="Previous"><i class="ai-chevron-left"></i></a></li>
                            <?php endif; ?>

                            <li class="page-item d-sm-none"><span class="page-link page-link-static"><?= $pagination->page();?> / <?= $pagination->pages();?></span></li>

                            <?php foreach ($pagination->range(10) as $r): ?>
                                <?php if ($pagination->page() === $r): ?>
                                    <li class="page-item active d-none d-sm-block" aria-current="page"><span class="page-link"><?= $r;?><span class="visually-hidden">(current)</span></span></li>
                                <?php else: ?>
                                    <li class="page-item d-none d-sm-block"><a class="page-link" href="<?= $pagination->pageURL($r) ?>"><?= $r ?></a></li>
                                <?php endif; ?>
                            <?php endforeach ?>

                            <?php if ($pagination->hasNextPage()): ?>
                                <li class="page-item"><a class="page-link" href="<?= $pagination->nextPageURL() ?>" aria-label="Next"><i class="ai-chevron-right"></i></a></li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<?php snippet('footer');?>
