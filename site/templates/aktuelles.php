<?php snippet('header'); ?>
<?php snippet('h1'); ?>
<div class="page">
    <div class="main-container container ps-sm-0 pe-sm-0 page--blog">
        <?php $firstArticle = $page->getArticles()->first();?>
        <?php if ($firstArticle): ?>
            <?php
            $firstTitle = (string)$firstArticle->title();
            $firstTeaser = $firstArticle->teaserimage()->isNotEmpty() ? $firstArticle->teaserimage()->toFile() : null;
            $firstTags = $firstArticle->getDisplayTags();
            ?>
            <article class="post-featured<?= $firstTeaser ? '' : ' post-featured--text'; ?>">
                <?php if ($firstTeaser): ?>
                    <a class="post-featured-media" href="<?= $firstArticle->url();?>" tabindex="-1" aria-hidden="true">
                        <img src="<?= $firstTeaser->resize(1500)->url();?>" alt="Artikelbild - <?= esc($firstTitle);?> - Sportfreunde Bronnen">
                    </a>
                <?php endif; ?>
                <div class="post-featured-body">
                    <div class="post-featured-label"><i class="fa fa-star-o"></i> Neuester Beitrag</div>
                    <?php if ($firstTags): ?>
                        <ul class="post-tags">
                            <?php foreach (array_slice($firstTags, 0, 3) as $tag): ?>
                                <li><?= esc($tag);?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                    <h2 class="post-featured-title">
                        <a href="<?= $firstArticle->url();?>" title="Zum Artikel: <?= esc($firstTitle);?>"><?= $firstTitle;?></a>
                    </h2>
                    <p class="post-featured-excerpt"><?= $firstArticle->getExcerpt(260);?></p>
                    <div class="post-featured-footer">
                        <div class="post-meta">
                            <time datetime="<?= $firstArticle->getDateIso();?>"><i class="fa fa-calendar-o"></i><?= $firstArticle->getDateLong();?></time>
                            <span><i class="fa fa-user-o"></i><?= esc($firstArticle->getAuthorName());?></span>
                        </div>
                        <a class="btn btn-1" href="<?= $firstArticle->url();?>" title="Zum Artikel: <?= esc($firstTitle);?>">Artikel lesen</a>
                    </div>
                </div>
            </article>
        <?php endif; ?>

        <h2 class="blog-section-title" id="archive--headline">Archiv</h2>
        <?php if ($page->children()->listed()->count() > 0): ?>
            <ul class="list-unstyled list-inline" id="article-filter">
                <li class="list-inline-item"><a class="btn<?= ('all' === $page->getActiveTagGroup()) ? ' active' : '';?>" data-group="all">Alles</a></li>
                <?php foreach ($page->getUsedTags() as $tag): ?>
                    <li class="list-inline-item"><a class="btn<?= ($tag === $page->getActiveTagGroup()) ? ' active' : '';?>" data-group="<?= trim($tag); ?>"><?= trim($tag); ?></a></li>
                <?php endforeach; ?>
            </ul>
            <div class="row blog-post" id="article-grid">
                <?php foreach ($page->getArticles()->slice(0, 1000) as $post): ?>
                    <div class="col-lg-4 col-md-6 col-sm-12 article-grid-item" data-groups='<?= $post->getNewsGroupsAsShuffleArray();?>'>
                        <?php snippet('post-card', ['post' => $post]); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="blog-empty">Aktuell sind keine Berichte vorhanden.</p>
        <?php endif; ?>
    </div>
</div>
<?php snippet('footer'); ?>
<script>
  $(function() {
    var $grid = $('#article-grid');
    $grid.shuffle({
      itemSelector: '.article-grid-item',
      speed: 500,
      group: '<?= $page->getActiveTagGroup();?>'
    });
    $('#article-filter li a').click(function (e) {
      // set active class
      $('#article-filter li a').removeClass('active');
      $(this).addClass('active');
      // get group name from clicked item
      var groupName = $(this).attr('data-group');
      // reshuffle grid
      $grid.shuffle('shuffle', groupName);
    });

    <?php if ($page->isInitialFiltering()): ?>
        $([document.documentElement, document.body]).animate({
          scrollTop: $("#archive--headline").offset().top - 80
        }, 1000);
    <?php endif; ?>

  });
</script>
<?php snippet('close'); ?>
