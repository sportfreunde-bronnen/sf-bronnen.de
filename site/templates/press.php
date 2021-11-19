<?php snippet('header'); ?>
<div class="page page--press">
    <?php snippet('h1'); ?>
    <div class="main-container container pl-sm-0 pr-sm-0">
        <h2 class="main-heading-1 text-spl-color text-weight-normal text-center text-lg-left"><?= $page->headline();?></h2>
        <?= $page->text()->kirbytext();?>
        <?php $articles = $page->articles()->toStructure();?>
        <?php if ($articles->count() > 0): ?>
            <div class="text-center mt-4">
                <ul class="list-unstyled list-inline" id="article-filter">
                    <li class="list-inline-item"><a href="#" class="btn animation<?= ('all' === $page->getActiveTagGroup()) ? ' active' : '';?>" data-group="all">Alles</a></li>
                    <?php foreach ($page->getUsedTags() as $tag): ?>
                        <li class="list-inline-item"><a href="#" class="btn animation<?= ($tag === $page->getActiveTagGroup()) ? ' active' : '';?>" data-group="<?= $tag; ?>"><?= $tag; ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="row blog-post" id="article-grid">
                <?php foreach ($articles as $article): ?>
                    <div class="col-lg-4 col-md-6 col-sm-12 article-grid-item" data-date-created="<?= $article->date()->toDate('Y-m-d');?>" data-groups='<?= $page->getNewsGroupsAsShuffleArray($article);?>'>
                        <div class="box-3 animation text-xs-center">
                            <div class="inner">
                                <p class="date-meta text-grey-color">
                                    <span class="badge badge-primary">
                                        <i class="fa fa-calendar"></i>&nbsp; <?= $article->date()->toDate('d.m.Y');?>
                                        Schwäbische Zeitung
                                    </span>
                                </p>
                                <h6 class="sub-heading-1 tiny text-medium text-xs-center">
                                    <a target="_blank" href="<?= $article->url();?>"><?= $article->title();?></a>
                                </h6>
                                <p class="text-grey-color">
                                    <?= substr($article->text(), 0, 100) . '...';?>
                                </p>
                                <p class="text-md-right">
                                    <a target="_blank" href="<?= $article->url();?>" class="more-link animation">Zum Artikel</a>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            Aktuell sind keine Berichteee vorhanden.
        <?php endif; ?>
    </div>
</div>
<?php snippet('footer'); ?>
<script>
  $(function() {

    function sortByDate(element) {
      return element.data('date-created');
    }

    var $grid = $('#article-grid');

    var $sortObject = {
        reverse: true,
        by: sortByDate
    };

    $grid.shuffle({
      itemSelector: '.article-grid-item',
      speed: 500,
      group: '<?= $page->getActiveTagGroup();?>',
      initialSort: $sortObject
    });

    $('#article-filter li a').click(function (e) {
      // set active class
      $('#article-filter li a').removeClass('active');
      $(this).addClass('active');
      // get group name from clicked item
      var groupName = $(this).attr('data-group');
      // reshuffle grid
      $grid.shuffle('shuffle', groupName);
      $grid.shuffle('sort', $sortObject);
    });
  });
</script>
<?php snippet('close'); ?>
