<?php
/**
 * Block "Button" of the default template (blueprint: site/blueprints/blocks/button.yml)
 *
 * @var \Kirby\Cms\Block $block
 */
$url = $block->link()->toUrl();
if ($url === null || $url === '' || $block->text()->isEmpty()) {
    return;
}
$style = (string)$block->style() === 'outline' ? 'outline' : 'primary';
$align = in_array((string)$block->align(), ['center', 'right'], true) ? (string)$block->align() : 'left';
$newTab = $block->newtab()->toBool();
?>
<div class="block-button block-button--<?= $align;?>">
    <a class="block-button__link block-button__link--<?= $style;?>" href="<?= esc($url, 'attr');?>"<?= $newTab ? ' target="_blank" rel="noopener noreferrer"' : '';?>><?= esc($block->text());?></a>
</div>
