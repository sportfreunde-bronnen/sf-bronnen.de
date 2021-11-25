<?php snippet('header'); ?>
<div class="page">
    <div class="container p-0 container--first--article">
        <div class="page page--single--article">
            <div class="first--article">
                <?php if ($page->getTitleImage()):?>
                    <img src="<?= $page->getTitleImage()->resize(1500)->url();?>"/>
                    <div class="first--article--headline">
                        <span class="title">
                            <?= $page->title();?>
                        </span>
                        <span class="date">
                            <?= date('d.m.Y', strtotime($page->datum()));?>
                            <?php if ($page->author2()->isEmpty()): ?>
                                <i class="fa fa-user ml-2"></i>&nbsp; <?= $page->author()->toUser()?->alias() ?? 'SFB';?>
                            <?php else: ?>
                                <i class="fa fa-user ml-2"></i>&nbsp; <?= sprintf('%s', $page->author2());?>
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="first--article--desc text-center text-md-right">
                        <?= $page->getTitleImage()->caption();?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="container pl-sm-0 pr-sm-0 page--bericht">
        <div class="row">
            <div class="col-12">
                <div class="blog-post single mt-0">
                    <div class="mb-3 box-3">
                        <div class="inner">
                            <?php if (!$page->getTitleImage()): ?>
                                <p class="date-meta text-grey-color pt-4">
                                <span class="badge badge-primary">
                                    <i class="fa fa-calendar"></i>&nbsp; <?= date('d.m.Y', strtotime($page->datum()));?>
                                    <?php if ($page->author2()->isEmpty()): ?>
                                        <i class="fa fa-user ml-2"></i>&nbsp; <?= sprintf('%s %s', site()->user($page->author())->firstName(), site()->user($page->author())->lastName());?>
                                    <?php else: ?>
                                        <i class="fa fa-user ml-2"></i>&nbsp; <?= sprintf('%s', $page->author2());?>
                                    <?php endif; ?>
                                </span>&nbsp
                                </p>
                                <h1 class="sub--1 tiny text-medium h3">
                                    <?= $page->title();?>
                                </h1>
                            <?php endif; ?>
                            <div class="blog-post-content pt-2">
                                <?php if ($moreImages = $page->getMoreImages()): ?>
                                    <?php $i = 0; foreach ($moreImages as $textImage): $i++; ?>
                                        <figure class="figure<?= ($i % 2 === 0) ? ' pull-right' : ' pull-right'; ?>">
                                            <img src="<?= $textImage->resize(500)->url();?>" class="figure-img img-fluid" alt="Bild: <?= $textImage->caption();?>">
                                            <figcaption class="figure-caption"><?= $textImage->caption();?></figcaption>
                                        </figure>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <?= $page->text()->kirbytext(); ?>
                            </div>
                            <div class="clearfix">
                                <h6 class="mb-0">Tags:</h6>
                                <ul class="list-unstyled list-inline blog-post-tags float-md-left animation">
                                    <?php foreach (explode(',', $page->tags()) as $tag): ?>
                                        <li class="list-inline-item"><i class="fa fa-tag"></i> <a href="<?= $page->parent()->url();?>?tag=<?= $tag;?>"><?= $tag;?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 right-column">

                <?php if ($page->galleryImages()->isNotEmpty()): ?>
                    <?php snippet('gallery', ['images' => $page->galleryImages()->toFiles(), 'desc' => $page->galleryName()]); ?>
                <?php endif; ?>

                <h5 class="sub-heading-2 mb-0">Auch interessant</h5>
                <div class="row">
                    <?php foreach ($page->getLatestNews()->slice(1,3) as $post): ?>
                        <div class="col-12 col-md-6 col-lg-4 news-box">
                            <a href="<?= $post->url();?>" title="Zum Artikel - <?= $post->title();?>">
                                <?php if ($post->teaserimage()->isNotEmpty()): ?>
                                  <img src="<?= $post->teaserimage()->toFile()->url();?>" alt="Artikelbild - <?= $post->title();?> - Sportfreunde Bronnen" class="img-fluid img-center">
                                <?php else: ?>
                                    <img src="/assets/images/blog/blog-thumb-2.jpg" alt="Artikelbild - <?= $post->title();?> - Sportfreunde Bronnen" class="img-fluid img-center">
                                <?php endif; ?>
                            </a>
                            <div class="news-box-content">
                                <div class="time-stamp text-center">
                                    <span class="text-weight-light"><?= date('d.m.Y', strtotime($post->datum()));?> - <?= $post->parents()->last()->title();?></span>
                                </div>
                                <h6 class="text-weight-medium"><a title="Zum Artikel - <?= $post->title();?>" href="<?= $post->url();?>"><?= $post->title();?></a></h6>
                                <hr/>
                                <p><?= substr($post->text(), 0, 100); ?>...</p>
                                <ul class="list-unstyled list-inline text-normal">
                                    <li class="list-inline-item text-weight-medium"><a class="h6" title="Zum Artikel - <?= $post->title();?>" href="<?= $post->url();?>">Weiterlesen</a></li>
                                </ul>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php snippet('footer'); ?><?php snippet('close'); ?>