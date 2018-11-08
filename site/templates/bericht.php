<?php snippet('header'); ?>
<div class="page">
    <div class="main-container container pl-sm-0 pr-sm-0 page--bericht">
        <div class="row">
            <div class="col-md-8 col-sm-12">
                <div class="blog-post single mt-3">
                    <div class="box-3 mb-0">
                        <div class="inner">
                            <p class="date-meta text-grey-color">
                                <span class="badge badge-primary">
                                    <i class="fa fa-calendar"></i>&nbsp; <?= date('d.m.Y', strtotime($page->datum()));?>
                                    <i class="fa fa-user ml-2"></i>&nbsp; <?= sprintf('%s %s', site()->user($page->author())->firstName(), site()->user($page->author())->lastName());?>
                                </span>&nbsp
                            </p>
                            <h1 class="sub--1 tiny text-medium h3">
                                <?= $page->title();?>
                            </h1>
                            <div class="blog-post-content">
                                <?= $page->text()->kirbytext(); ?>
                            </div>
                            <div class="clearfix">
                                <ul class="list-unstyled list-inline blog-post-tags float-md-left animation">
                                    <li class="list-inline-item"><h6>Tags: </h6></li>
                                    <?php foreach (explode(',', $page->tags()) as $tag): ?>
                                        <li class="list-inline-item"><i class="fa fa-tag"></i> <a href="#"><?= $tag;?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-100 d-xs-block d-sm-block d-md-none">
                <p class="spacer-small"></p>
            </div>
            <div class="col-md-4 col-sm-12 right-column">
                <?php if ($page->textImages()->isNotEmpty()): ?>
                    <div class="mb-3 images">
                    <?php foreach (explode(',', $page->textImages()) as $imageUrl): $image = $page->images()->find($imageUrl); ?>
                    <figure>
                        <img src="<?= $image->resize(750)->url();?>" class="img-fluid mb-2" alt="<?= $image->caption();?> - Sportfreunde Bronnen"/>
                        <figcaption>
                            <small><?= $image->caption();?></small>
                        </figcaption>
                    </figure>
                    <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <h5 class="sub-heading-2 mb-0">Weitere Artikel</h5><br>
                <ul class="list-unstyled list-post-1">
                <?php foreach ($site->index()->visible()->filterBy('template', 'bericht')->sortBy('datum', 'desc')->limit(3) as $post): ?>
                    <?php if ($post === $page) continue; ?>
                    <li>
                        <h6 class="sub-heading-1"><?= $post->title();?></h6>
                        <div class="row">
                            <div class="col-12">
                                <p><?= substr($post->text(), 0, 50) . '...';?></p>
                                <a href="<?= $post->url();?>" class="more-link animation" title="Zum Artikel: <?= $post->title();?>">&raquo; Weiterlesen</a>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
                </ul>
                <div class="spacer-medium mb-3"></div>
                <div class="advertising-hint">#nurdiesfb</div>
            </div>
        </div>
        <?php if ($page->galleryImages()->isNotEmpty()): ?>
            <?php snippet('gallery', ['images' => $page->convertFilesToCollection($page->galleryImages()), 'desc' => $page->galleryName()]); ?>
        <?php endif; ?>
    </div>
</div>
<?php snippet('footer'); ?>