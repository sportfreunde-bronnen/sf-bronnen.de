<?php
/**
 * Block "Abstand" of the default template (blueprint: site/blueprints/blocks/spacer.yml)
 *
 * @var \Kirby\Cms\Block $block
 */
$size = in_array((string)$block->size(), ['small', 'large'], true) ? (string)$block->size() : 'medium';
?>
<div class="block-spacer block-spacer--<?= $size;?>" aria-hidden="true"></div>
