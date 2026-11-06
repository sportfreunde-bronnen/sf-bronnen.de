<?php
/**
 * Blog card for an article ("bericht")
 *
 * @var \Kirby\Cms\Page $post
 * @var string|null $areaTitle optional: name of the area (e.g. "Fußball"), shown as first chip
 */
$title = (string)$post->title();
$teaser = $post->teaserimage()->isNotEmpty() ? $post->teaserimage()->toFile() : null;
$areaTitle = $areaTitle ?? null;
$tags = $post->getDisplayTags();
if ($areaTitle) {
    // the area is shown on its own, a tag with the same name would only repeat it
    $tags = array_values(array_filter($tags, function ($tag) use ($areaTitle) {
        return mb_strtolower($tag) !== mb_strtolower($areaTitle);
    }));
}
?>
<article class="post-card">
    <a class="post-card-media" href="<?= $post->url();?>" tabindex="-1" aria-hidden="true">
        <?php if ($teaser): ?>
            <img src="<?= $teaser->resize(750)->url();?>" alt="Artikelbild - <?= esc($title);?> - Sportfreunde Bronnen" loading="lazy">
        <?php else: ?>
            <img src="/assets/images/blog/blog-thumb-2.jpg" alt="Artikelbild - <?= esc($title);?> - Sportfreunde Bronnen" loading="lazy">
        <?php endif; ?>
    </a>
    <div class="post-card-body">
        <?php if ($areaTitle || $tags): ?>
            <ul class="post-tags">
                <?php if ($areaTitle): ?>
                    <li class="post-tag--area"><?= esc($areaTitle);?></li>
                <?php endif; ?>
                <?php foreach (array_slice($tags, 0, $areaTitle ? 1 : 2) as $tag): ?>
                    <li><?= esc($tag);?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <h3 class="post-card-title">
            <a href="<?= $post->url();?>" title="Zum Artikel: <?= esc($title);?>"><?= $title;?></a>
        </h3>
        <p class="post-card-excerpt"><?= $post->getExcerpt(150);?></p>
        <div class="post-card-footer">
            <div class="post-meta">
                <time datetime="<?= $post->getDateIso();?>"><i class="fa fa-calendar-o"></i><?= $post->getDateLong();?></time>
                <span><i class="fa fa-user-o"></i><?= esc($post->getAuthorName());?></span>
            </div>
            <a href="<?= $post->url();?>" class="post-more" title="Zum Artikel: <?= esc($title);?>">Weiterlesen <i class="fa fa-long-arrow-right"></i></a>
        </div>
    </div>
</article>
