<div class="tns-carousel-wrapper">
    <div class="tns-carousel-inner" data-carousel-options="{&quot;nav&quot;: false, &quot;gutter&quot;: 23, &quot;responsive&quot;: {&quot;0&quot;:{&quot;items&quot;:1},&quot;768&quot;:{&quot;items&quot;:2},&quot;992&quot;:{&quot;items&quot;:3}}}">
        <?php foreach ($news as $post): ?>
            <div class="masonry-grid-item">
                <article class="card card-hover"><a class="card-img-top" href="<?= $post->url();?>"><img src="<?= $post->teaserimage()->toFile()->crop(510, 340, 'top left')->url(); ?>" alt="Artikelbild - <?= $post->title();?> - Sportfreunde Bronnen"></a>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="ai-calendar"></i><span class="p-2 d-flex"><small><?= $post->datum()->toDate('d.m.Y');?></small></span>
                        </div>
                        <h2 class="h5 nav-heading mb-4">
                            <a href="<?= $post->url();?>" title="Zum Artikel - <?= $post->title();?>" href="<?= $post->url();?>">
                                <?= $post->title();?>
                            </a>
                        </h2>
                        <a class="meta-link fs-sm mb-2" href="<?= $post->url();?>" title="Zum Artikel - <?= $post->title();?>" href="<?= $post->url();?>">
                            <?= substr(strip_tags($post->text()->kirbytext()), 0, 100); ?>...
                        </a>
                    </div>
                </article>
            </div>
        <?php endforeach; ?>
    </div>
</div>