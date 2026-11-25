<?php
/**
 * Block "Aufklappbereich" of the default template (blueprint: site/blueprints/blocks/accordion.yml)
 *
 * @var \Kirby\Cms\Block $block
 */
?>
<details class="block-accordion"<?= $block->open()->toBool() ? ' open' : '';?>>
    <summary class="block-accordion__title"><?= esc($block->title());?></summary>
    <div class="block-accordion__text"><?= $block->text();?></div>
</details>
