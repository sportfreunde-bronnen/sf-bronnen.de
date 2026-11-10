<?php
/**
 * Block "Galerie" of the default template (blueprint: site/blueprints/blocks/gallery.yml)
 *
 * Grid of equally sized thumbnails, a click opens the images in the lightbox (Magnific Popup, see public/assets/js/custom.js).
 * The description of the file is used as caption in the lightbox.
 *
 * @var \Kirby\Cms\Block $block
 */
$images = $block->images()->toFiles();
if ($images->isEmpty()) {
    return;
}
$columns = in_array((string)$block->columns(), ['2', '3', '4'], true) ? (string)$block->columns() : '3';
$caption = trim((string)$block->caption()->value());
$title = (string)$block->parent()->title();
?>
<figure class="block-gallery block-gallery--cols-<?= $columns;?> js-lightbox-gallery">
    <ul class="block-gallery__grid">
        <?php foreach ($images as $image): ?>
            <?php
            $description = trim((string)$image->description()->value());
            $thumbnail = $image->crop(1000, 750);
            ?>
            <li class="block-gallery__item">
                <a class="block-gallery__link" href="<?= $image->resize(2000)->url();?>" data-caption="<?= esc($description, 'attr');?>">
                    <img src="<?= $thumbnail->url();?>" width="<?= $thumbnail->width();?>" height="<?= $thumbnail->height();?>" alt="<?= esc($description ?: $title, 'attr');?>" loading="lazy">
                    <span class="block-gallery__zoom" aria-hidden="true"><i class="fa fa-search-plus"></i></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
    <?php if ($caption !== ''): ?>
        <figcaption class="block-figure__caption"><?= $caption;?></figcaption>
    <?php endif; ?>
</figure>
