<?php
/**
 * Block "Spalten" of the default template (blueprint: site/blueprints/blocks/columns.yml)
 *
 * Two or three columns, each one holds its own blocks. On small screens the columns are stacked.
 *
 * @var \Kirby\Cms\Block $block
 */
$layouts = ['50-50' => 2, '33-67' => 2, '67-33' => 2, '33-33-33' => 3];
$layout = (string)$block->layout();
if (!isset($layouts[$layout])) {
    $layout = '50-50';
}
?>
<div class="block-columns block-columns--<?= $layout;?>">
    <?php for ($i = 1; $i <= $layouts[$layout]; $i++): ?>
        <div class="block-columns__column">
            <?php foreach ($block->content()->get('column' . $i)->toBlocks()->filterBy('isHidden', false) as $inner): ?>
                <?php $html = trim((string)$inner); ?>
                <?php if ($html !== ''): ?>
                    <div class="block block--<?= $inner->type();?>"><?= $html;?></div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endfor; ?>
</div>
