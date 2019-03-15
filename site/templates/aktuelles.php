<?php snippet('header'); ?>
<?php snippet('h1'); ?>
<div class="page page--articles">
    <div class="main-container container pl-sm-0 pr-sm-0">
        <?php if ($page->children()->visible()->count() > 0): ?>
            <div class="text-center">
                <ul class="list-unstyled list-inline" id="article-filter">
                    <li class="list-inline-item"><a href="#" class="btn animation<?= ('all' === $page->getActiveTagGroup()) ? ' active' : '';?>" data-group="all">Alles</a></li>
                    <?php foreach ($page->getUsedTags() as $tag): ?>
                        <li class="list-inline-item"><a href="#" class="btn animation<?= ($tag === $page->getActiveTagGroup()) ? ' active' : '';?>" data-group="<?= $tag; ?>"><?= $tag; ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="row blog-post" id="article-grid">
                <?php foreach ($page->getArticles() as $post): ?>
                    <div class="col-lg-4 col-md-6 col-sm-12 article-grid-item" data-groups='<?= $post->getNewsGroupsAsShuffleArray();?>'>
                        <div class="box-3 animation text-xs-center">
                            <p>
                                <?php if ($post->teaserimage()->isNotEmpty()): ?>
                                    <a href="<?= $post->url();?>">
                                        <img src="<?= $site->find($post->uri())->images()->find($post->teaserimage())->resize(750)->url();?>" alt="Blog Image" class="img-fluid img-center-xs">
                                    </a>
                                <?php endif; ?>
                            </p>
                            <div class="inner">
                                <p class="date-meta text-grey-color">
                                    <span class="badge badge-primary">
                                        <i class="fa fa-calendar"></i>&nbsp; <?= date('d.m.Y', strtotime($post->datum()));?>
                                        <?php if ($post->author2()->isEmpty()): ?>
                                            <i class="fa fa-user ml-3"></i>&nbsp; <?= sprintf('%s %s', site()->user($post->author())->firstName(), site()->user($post->author())->lastName());?>
                                        <?php else: ?>
                                            <i class="fa fa-user ml-3"></i>&nbsp;&nbsp; <?= sprintf('%s', $post->author2());?>
                                        <?php endif; ?>
                                    </span>
                                </p>
                                <h6 class="sub-heading-1 tiny text-medium text-xs-center">
                                    <a href="<?= $post->url();?>"><?= $post->title();?></a>
                                </h6>
                                <p class="text-grey-color">
                                    <?= substr($post->text(), 0, 100) . '...';?>
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
  });
</script>
<?php snippet('close'); ?>
