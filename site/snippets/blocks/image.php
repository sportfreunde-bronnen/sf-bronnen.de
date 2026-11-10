<?php
/**
 * Block "Bild" of the default template (blueprint: site/blueprints/blocks/image.yml)
 *
 * @var \Kirby\Cms\Block $block
 */
$image = $block->image()->toFile();
if (!$image) {
    return;
}
$medium = (string)$block->size() === 'medium';
?>
<?php snippet('block-image', [
    'image' => $image,
    'alt' => (string)$block->alt(),
    'caption' => $block->caption(),
    'class' => $medium ? 'block-figure--medium' : '',
    'sizes' => $medium ? '(min-width: 768px) 640px, 100vw' : '(min-width: 1200px) 1050px, 100vw',
]); ?>
