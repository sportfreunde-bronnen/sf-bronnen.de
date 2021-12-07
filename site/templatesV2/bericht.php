<?php snippet('header');?>
    <div class="container">
        <div class="row justify-content-center">
            <!-- Content-->
            <div class="col-lg-10 py-4 mb-2 mb-sm-0 pb-sm-5">
                <div class="pb-4">
                    <?php if ($page->disableHeader()->toBool() === true): ?>
                    <h1><?= $page->title();?></h1>
                    <?php endif; ?>
                </div>
                <!-- Post author + Sharing-->
                <div class="row position-relative g-0 align-items-center border-top border-bottom mb-4">
                    <div class="col-md-6 py-3 pe-md-3">
                        <div class="d-flex align-items-center justify-content-center justify-content-md-start">
                            <div class="d-flex align-items-center me-grid-gutter">
                                <div class="ps-2">
                                    <h6 class="nav-heading mb-1"><a href="#"><?= $page->author()->toUser()?->alias() ?? 'Autor';?></a></h6>
                                    <div class="text-nowrap">
                                        <div class="meta-link fs-xs"><i class="ai-calendar me-1 align-vertical"></i>&nbsp;<?= $page->datum()->toDate('d.m.Y');?></div></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-none d-md-block position-absolute border-start h-100" style="top: 0; left: 50%; width: 1px;"></div>
                    <div class="col-md-6 ps-md-3 py-3">
                        <div class="d-flex align-items-center justify-content-center justify-content-md-end">
                            <h6 class="text-nowrap my-2 me-3">Share post:</h6><a class="btn-social bs-outline bs-facebook ms-2 my-2" href="#"><i class="ai-facebook"></i></a><a class="btn-social bs-outline bs-twitter ms-2 my-2" href="#"><i class="ai-twitter"></i></a><a class="btn-social bs-outline bs-google ms-2 my-2" href="#"><i class="ai-google"></i></a><a class="btn-social bs-outline bs-email ms-2 my-2" href="#"><i class="ai-mail"></i></a>
                        </div>
                    </div>
                </div>
                <div class="py-4">
                    <?php if ($moreImages = $page->getMoreImages()): ?>
                        <?php $i = 0; foreach ($moreImages as $textImage): $i++; ?>
                            <figure class="figure px-0 text-md-start<?= ($i % 2 == 0) ? ' float-none float-md-end pe-0' : ' float-none float-md-start ps-0'; ?>">
                                <img src="<?= $textImage->resize(500)->url();?>" class="figure-img img-fluid" alt="Bild: <?= $textImage->caption();?>">
                                <figcaption class="figure-caption w-75"><?= $textImage->caption();?></figcaption>
                            </figure>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?= $page->text()->kirbytext();?>
                </div>
                <!--
                <h2 class="h3 pt-1 pb-4">Expert opinion</h2>
                <div class="bg-faded-primary rounded-3 p-4 mb-4">
                    <div class="p-md-3">
                        <blockquote class="blockquote mb-0">
                            <p class="text-nav">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Quis ipsum suspendisse ultrices gravida dignissimos ducimus qui blanditiis praesentium voluptatum.</p>
                            <p class="text-nav">Mattis enim ut tellus elementum sagittis vitae et leo duis.</p>
                            <footer class="blockquote-footer">Miguel Sánchez</footer>
                        </blockquote>
                    </div>
                </div>
                -->

                <!-- Bildergalerie-->
                <?php if ($page->galleryImages()->isNotEmpty()): ?>
                    <?php snippet('gallery', ['images' => $page->galleryImages()->toFiles(), 'desc' => $page->galleryName()]); ?>
                <?php endif; ?>

                <!-- Tags + Sharing-->
                <div class="row g-0 position-relative align-items-center">
                    <div class="my-5 py-2 py-dm-3 pe-md-3 text-center text-md-start border-top border-bottom">
                        <?php foreach (explode(',', $page->tags()) as $tag): ?>
                            <a class="btn-tag me-2 my-2" href="<?= $page->parent()->url();?>?tag=<?= trim($tag);?>"><?= trim($tag);?></a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Related posts (carousel)-->
                <!--
                <div class="pb-4 pb-md-5">
                    <h2 class="h3 pb-4">Related posts</h2>
                    <div class="tns-carousel-wrapper">
                        <div class="tns-carousel-inner" data-carousel-options="{&quot;items&quot;: 2, &quot;controls&quot;: false, &quot;autoHeight&quot;: true, &quot;responsive&quot;: {&quot;0&quot;:{&quot;items&quot;:1, &quot;gutter&quot;: 16},&quot;500&quot;:{&quot;items&quot;:2, &quot;gutter&quot;: 16},&quot;850&quot;:{&quot;items&quot;:3, &quot;gutter&quot;: 16}, &quot;1100&quot;:{&quot;items&quot;:3, &quot;gutter&quot;: 23}}}">
                            <div class="pb-2">
                                <article class="card card-hover mx-1"><a class="card-img-top" href="#"><img src="img/blog/02.jpg" alt="Post thumbnail"></a>
                                    <div class="card-body"><a class="meta-link fs-sm mb-2" href="#">Technology</a>
                                        <h2 class="h5 nav-heading mb-4"><a href="#">How technology affect our decisions</a></h2><a class="d-flex meta-link fs-sm align-items-center pt-3" href="#"><img class="rounded-circle" src="img/blog/avatar/03.jpg" alt="Jessica Miller" width="36">
                                            <div class="ps-2 ms-1 mt-n1">by<span class="fw-semibold ms-1">Jessica Miller</span></div></a>
                                        <div class="mt-3 text-end text-nowrap"><a class="meta-link fs-xs" href="#"><i class="ai-message-square me-1"></i>&nbsp;3</a><span class="meta-divider"></span><a class="meta-link fs-xs" href="#"><i class="ai-calendar me-1 mt-n1"></i>&nbsp;Feb 4</a></div>
                                    </div>
                                </article>
                            </div>
                        </div>
                    </div>
                </div>
                -->
            </div>
        </div>
    </div>
<?php snippet('footer');?>