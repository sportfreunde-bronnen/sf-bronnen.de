<?php if ($page->template() === 'bericht'): ?>
    <title><?= date('d.m.Y', strtotime($page->datum())) . ' - ' . $page->title();?> - <?= $site->title();?></title>
<?php elseif (in_array($page->template(),  ['aktuelles', 'infos', 'ah'])): ?>
    <title><?= $page->title(); ?> - <?= $page->parents()->last()->title();?> - <?= $site->title();?></title>
<?php else: ?>
    <title><?= $page->title();?> - <?= $site->title();?></title>
<?php endif; ?>