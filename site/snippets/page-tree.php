<?php
/**
 * Tree of the given pages and all their listed subpages (used for empty structure pages, see template default).
 *
 * Level 0: grid of cards (one per page), deeper levels: nested lists. Pages with subpages can be collapsed
 * (<details>, works without JavaScript), assets/js/page-tree.js adds filter and expand/collapse all.
 *
 * @var \Kirby\Cms\Pages $items
 * @var int $level
 */
$level = $level ?? 0;
?>
<?php if ($level === 0): ?>
<div class="page-tree__grid">
    <?php foreach ($items as $index => $item): ?>
        <?php $children = $item->children()->listed(); ?>
        <section class="page-tree__card" style="--i: <?= $index;?>">
            <h2 class="page-tree__card-title">
                <a href="<?= $item->url();?>"><?= $item->title();?></a>
                <?php if ($children->isNotEmpty()): ?>
                    <span class="page-tree__count" title="<?= $children->count();?> Unterseiten"><?= $children->count();?></span>
                <?php endif; ?>
            </h2>
            <?php if ($children->isNotEmpty()): ?>
                <?php snippet('page-tree', ['items' => $children, 'level' => 1]);?>
            <?php endif; ?>
        </section>
    <?php endforeach; ?>
</div>
<?php else: ?>
<ul class="page-tree__list">
    <?php foreach ($items as $item): ?>
        <?php $children = $item->children()->listed(); ?>
        <li class="page-tree__item">
            <?php if ($children->isNotEmpty()): ?>
                <details class="page-tree__details">
                    <summary>
                        <a href="<?= $item->url();?>"><?= $item->title();?></a>
                        <span class="page-tree__count"><?= $children->count();?></span>
                    </summary>
                    <?php snippet('page-tree', ['items' => $children, 'level' => $level + 1]);?>
                </details>
            <?php else: ?>
                <a href="<?= $item->url();?>"><?= $item->title();?></a>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>
