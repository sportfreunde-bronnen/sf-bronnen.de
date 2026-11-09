<section class="main-banner text-xs-center text-sm-center text-md-start">
    <div class="container text-lite-color ps-sm-0 pe-sm-0">
        <?php if ($page->template() === 'aktuelles') : ?>
            <h1 class="text-weight-medium"><?= $page->title(); ?> - <?= $page->parents()->last()->title() . APPEND_TITLE;;?></h1>
        <?php else: ?>
            <h1 class="text-weight-medium"><?= $page->title() . APPEND_TITLE;; ?></h1>
        <?php endif; ?>
    </div>
</section>