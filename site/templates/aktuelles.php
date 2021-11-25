<?php snippet('header'); ?>
<?php snippet('h1'); ?>
    <div class="container p-0 container--first--article">
        <div class="page page--articles">
            <?php $firstArticle = $page->children()->listed()->sortBy('datum', 'desc')->first();?>
            <?php if ($firstArticle): ?>
                <div class="first--article mb-5">
                    <?php if ($firstArticle->teaserimage()->isNotEmpty()):?>

                        <a href="<?= $firstArticle->url();?>" title="Zum Artikel: <?= $firstArticle->title();?>">
                            <img src="<?= $firstArticle->teaserimage()->toFile()->resize(1500)->url();?>"/>
                        </a>
                        <div class="first--article--headline">
                            <span class="title">
                                <a href="<?= $firstArticle->url();?>" title="Zum Artikel: <?= $firstArticle->title();?>"><?= $firstArticle->title();?></a>
                            </span>
                            <span class="date">
                                <?= date('d.m.Y', strtotime($firstArticle->datum()));?>
                            </span>
                            <a class="btn btn-1 animation pull-right" href="<?= $firstArticle->url();?>" title="Zum Artikel: <?= $firstArticle->title();?>">
                                Artikel lesen
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="main-container container pl-sm-0 pr-sm-0 pt-0">
        <h5 class="sub-heading-2 mb-5" id="archive--headline">Archiv</h5>
        <?php if ($page->children()->listed()->count() > 0): ?>
            <div class="text-center">
                <ul class="list-unstyled list-inline" id="article-filter">
                    <li class="list-inline-item"><a class="btn animation<?= ('all' === $page->getActiveTagGroup()) ? ' active' : '';?>" data-group="all">Alles</a></li>
                    <?php foreach ($page->getUsedTags() as $tag): ?>
                        <li class="list-inline-item"><a class="btn animation<?= ($tag === $page->getActiveTagGroup()) ? ' active' : '';?>" data-group="<?= trim($tag); ?>"><?= trim($tag); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="row blog-post" id="article-grid">
                <?php foreach ($page->getArticles()->slice(0, 1000) as $post): ?>
                    <div class="col-lg-4 col-md-6 col-sm-12 article-grid-item" data-groups='<?= $post->getNewsGroupsAsShuffleArray();?>'>
                        <div class="box-3 animation text-xs-center">
                            <p>
                                <?php if ($post->teaserimage()->isNotEmpty()): ?>
                                    <a href="<?= $post->url();?>">
                                        <img src="<?= $post->teaserimage()->toFile()->resize(750)->url();?>" alt="Artikelbild - <?= $post->title();?> - Sportfreunde Bronnen" class="img-fluid img-center-xs">
                                    </a>
                                <?php endif; ?>
                            </p>
                            <div class="inner">
                                <p class="date-meta text-grey-color">
                                    <span class="badge badge-primary">
                                        <i class="fa fa-calendar"></i>&nbsp; <?= date('d.m.Y', strtotime($post->datum()));?>
                                        <?php if ($post->author2()->isEmpty()): ?>
                                            <i class="fa fa-user ml-3"></i>&nbsp; <?= $post->author()->toUser()?->alias() ?? 'SFB';?>
                                        <?php else: ?>
                                            <i class="fa fa-user ml-3"></i>&nbsp;&nbsp; <?= sprintf('%s', $post->author2());?>
                                        <?php endif; ?>
                                    </span>
                                </p>
                                <h6 class="sub-heading-1 tiny text-medium text-xs-center">
                                    <a href="<?= $post->url();?>"><?= $post->title();?></a>
                                </h6>
                                <p class="text-grey-color">
                                    <?= substr(strip_tags($post->text()->kirbytext()), 0, 100); ?>...
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
