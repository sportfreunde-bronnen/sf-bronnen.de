<?php
/**
 * Menu entries of the modern header (see snippet header-modern), same structure as the classic navigation:
 * top level pages with an optional dropdown of their subpages (and the pages below a subpage as group).
 */
$topLevelPages = $pages->listed();
$topLevelCount = $topLevelPages->count();
$topLevelIndex = 0;
?>
<?php foreach ($topLevelPages as $topLevelPage): ?>
    <?php
    $subPages = $topLevelPage->children()->listed();
    // the dropdowns of the right half open to the left so they never leave the window
    $alignRight = $topLevelIndex++ >= ceil($topLevelCount / 2);
    ?>
    <?php if ($subPages->count()): ?>
        <li class="nav-item dropdown<?= $topLevelPage->isOpen() ? ' active' : '';?>">
            <a href="<?= $topLevelPage->url();?>" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?= $topLevelPage->title();?></a>
            <div class="dropdown-menu<?= $alignRight ? ' dropdown-menu-end' : '';?>">
                <?php foreach ($subPages as $subPage): ?>
                    <?php if ($subPage->hasChildren()): ?>
                        <span class="mh-dropdown-label"><?= $subPage->title();?></span>
                        <?php if ($subPage->title() == 'Veranstaltungen'): ?>
                            <?php $groupPages = $subPage->children()->listed()->sortBy('datum'); ?>
                        <?php else: ?>
                            <?php $groupPages = $subPage->children()->listed(); ?>
                        <?php endif; ?>
                        <?php foreach ($groupPages as $groupPage): ?>
                            <a class="dropdown-item<?= $groupPage->isOpen() ? ' active' : '';?>" href="<?= $groupPage->url();?>"><?= $groupPage->title();?></a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <a class="dropdown-item<?= $subPage->isOpen() ? ' active' : '';?>" href="<?= $subPage->url();?>"><?= $subPage->title();?></a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </li>
    <?php else: ?>
        <li class="nav-item<?= $topLevelPage->isOpen() ? ' active' : '';?>"><a href="<?= $topLevelPage->url();?>" class="nav-link"><?= $topLevelPage->title();?></a></li>
    <?php endif; ?>
<?php endforeach; ?>
