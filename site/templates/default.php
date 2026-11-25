<?php snippet('header'); ?>
<section class="main-banner text-xs-center text-sm-center text-md-start">
    <div class="container text-lite-color ps-sm-0 pe-sm-0">
        <h1 class="text-weight-medium"><?= $page->title() . APPEND_TITLE;;?></h1>
    </div>
</section>
<?php
$hasHeadline = $page->headline()->isNotEmpty();
$hasText = $page->text()->isNotEmpty();
// content blocks (text, images, galleries ...): they place the images of the page themselves,
// pages without blocks show all images of the page as grid below the text
$blocks = $page->blocks()->toBlocks()->filterBy('isHidden', false);
$hasBlocks = $blocks->isNotEmpty();
$images = $hasBlocks ? null : $page->images();
$hasImages = $images !== null && $images->count() > 0;
$hasPeople = $page->people()->isNotEmpty();
// pages without any content only structure the navigation: show the pages below instead of an empty page
$isEmpty = !$hasHeadline && !$hasText && !$hasBlocks && !$hasImages && !$hasPeople;
$subPages = $isEmpty ? $page->children()->listed() : null;
$showTree = $subPages !== null && $subPages->isNotEmpty();
?>
<div class="page">
<div class="main-container container ps-sm-0 pe-sm-0">
    <?php if ($hasHeadline || $hasText || $hasBlocks || $hasImages): ?>
        <article class="content-card<?= $hasBlocks ? ' content-card--blocks' : '';?>">
            <?php if ($hasHeadline): ?>
                <h2 class="main-heading-1 text-spl-color text-weight-normal text-center text-lg-start"><?= $page->headline();?></h2>
            <?php endif; ?>
            <?php if ($hasText): ?>
                <div class="content-card__text">
                    <?= $page->text()->kirbytext();?>
                </div>
            <?php endif; ?>
            <?php if ($hasBlocks): ?>
                <div class="content-blocks">
                    <?php foreach ($blocks as $block): ?>
                        <?php $html = trim((string)$block); ?>
                        <?php if ($html !== ''): ?>
                            <div class="block block--<?= $block->type();?>"><?= $html;?></div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php if ($hasImages): ?>
                <?php if ($hasText): ?>
                    <hr/>
                <?php endif; ?>
                <div class="row">
                    <?php foreach($images as $image): ?>
                        <div class="col-12 col-md-6 col-lg-4 col-xl-3 text-center mb-3">
                            <figure class="figure">
                                <img src="<?= $image->resize(750)->url();;?>" class="figure-img img-fluid">
                                <figcaption class="figure-caption"><?= $image->caption();?></figcaption>
                            </figure>
                            <hr class="d-md-none"/>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </article>
    <?php endif; ?>
    <?php if ($showTree): ?>
        <div class="page-tree" role="navigation" aria-label="Unterseiten von <?= esc($page->title(), 'attr');?>">
            <div class="page-tree__toolbar">
                <input type="search" class="page-tree__filter" placeholder="Seiten filtern ..." aria-label="Unterseiten filtern" hidden>
                <button type="button" class="page-tree__toggle" data-expanded="false" hidden>Alle aufklappen</button>
            </div>
            <?php snippet('page-tree', ['items' => $subPages]);?>
            <p class="page-tree__empty" hidden>Keine passende Seite gefunden.</p>
        </div>
    <?php endif; ?>
    <?php if ($hasPeople): ?>
        <?php snippet('people');?>
    <?php endif; ?>
</div>
</div>
<?php snippet('footer', $showTree ? ['scripts' => ['page-tree']] : []); ?><?php snippet('close'); ?>
