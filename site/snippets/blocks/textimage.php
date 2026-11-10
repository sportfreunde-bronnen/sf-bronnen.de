<?php
/**
 * Block "Text mit Bild" of the default template (blueprint: site/blueprints/blocks/textimage.yml)
 *
 * On wide screens text and image are side by side (position of the image: left or right), on small screens the image is shown above the text.
 *
 * @var \Kirby\Cms\Block $block
 */
$image = $block->image()->toFile();
$position = (string)$block->position() === 'left' ? 'left' : 'right';
?>
<div class="block-textimage block-textimage--<?= $image ? $position : 'noimage';?>">
    <?php if ($image): ?>
        <div class="block-textimage__media">
            <?php snippet('block-image', [
                'image' => $image,
                'alt' => (string)$block->alt(),
                'caption' => $block->caption(),
                'sizes' => '(min-width: 992px) 440px, (min-width: 768px) 40vw, 100vw',
                'widths' => [400, 640, 900, 1200],
            ]); ?>
        </div>
    <?php endif; ?>
    <div class="block-textimage__text">
        <?= $block->text();?>
    </div>
</div>
