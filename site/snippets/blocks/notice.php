<?php
/**
 * Block "Hinweisbox" of the default template (blueprint: site/blueprints/blocks/notice.yml)
 *
 * @var \Kirby\Cms\Block $block
 */
$kind = in_array((string)$block->kind(), ['success', 'warning'], true) ? (string)$block->kind() : 'info';
?>
<div class="block-notice block-notice--<?= $kind;?>">
    <?php if ($block->title()->isNotEmpty()): ?>
        <p class="block-notice__title"><?= esc($block->title());?></p>
    <?php endif; ?>
    <div class="block-notice__text"><?= $block->text();?></div>
</div>
