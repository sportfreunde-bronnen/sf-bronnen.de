<?php
/**
 * Social links of the club as list items, to be placed inside an <ul> by the caller.
 *
 * Optional parameters:
 * - $itemClass: CSS class of the <li> elements (default: list-inline-item)
 */
$itemClass = $itemClass ?? 'list-inline-item';
$socialLinks = [
    ['url' => 'https://www.instagram.com/sfb1949/', 'title' => 'Die Sportfreunde Bronnen auf Instagram', 'icon' => 'fa-instagram'],
    ['url' => 'https://www.facebook.com/sportfreundebronnen/', 'title' => 'Die Sportfreunde Bronnen auf Facebook', 'icon' => 'fa-facebook'],
    ['url' => 'https://shop.sf-bronnen.de', 'title' => 'Der Online Shop der Sportfreunde Bronnen', 'icon' => 'fa-shopping-cart'],
];
?>
<?php foreach ($socialLinks as $socialLink): ?>
<li class="<?= $itemClass;?>"><a href="<?= $socialLink['url'];?>" title="<?= $socialLink['title'];?>" aria-label="<?= $socialLink['title'];?>"><i class="fa <?= $socialLink['icon'];?>" aria-hidden="true"></i></a></li>
<?php endforeach; ?>
