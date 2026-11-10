<?php
/**
 * Image with caption of a content block (blocks "Bild" and "Text mit Bild" of the default template).
 * A click opens the large image in the lightbox (Magnific Popup, see public/assets/js/custom.js).
 *
 * @var \Kirby\Cms\File $image
 * @var string|null $alt alternative text, defaults to the description of the file
 * @var \Kirby\Cms\Field|null $caption caption (HTML of the writer field)
 * @var string|null $sizes "sizes" attribute of the image
 * @var int[]|null $widths widths of the responsive image candidates
 * @var string|null $class additional CSS classes of the figure
 */
$captionHtml = isset($caption) ? trim((string)$caption->value()) : '';
$captionText = trim(strip_tags($captionHtml));
$alt = trim((string)($alt ?? '')) ?: trim((string)$image->description()->value()) ?: $captionText ?: (string)$image->parent()->title();
$sizes = $sizes ?? '(min-width: 1200px) 1050px, 100vw';
$widths = $widths ?? [480, 800, 1200, 1600];
$class = trim('block-figure ' . ($class ?? ''));
$lightboxCaption = $captionText ?: trim((string)$image->description()->value());
?>
<figure class="<?= esc($class, 'attr');?>">
    <a class="block-figure__link js-lightbox" href="<?= $image->resize(2000)->url();?>" data-caption="<?= esc($lightboxCaption, 'attr');?>">
        <img src="<?= $image->resize(1200)->url();?>" srcset="<?= $image->srcset($widths);?>" sizes="<?= esc($sizes, 'attr');?>" width="<?= $image->width();?>" height="<?= $image->height();?>" alt="<?= esc($alt, 'attr');?>" loading="lazy">
        <span class="block-figure__zoom" aria-hidden="true"><i class="fa fa-search-plus"></i></span>
    </a>
    <?php if ($captionHtml !== ''): ?>
        <figcaption class="block-figure__caption"><?= $captionHtml;?></figcaption>
    <?php endif; ?>
</figure>
