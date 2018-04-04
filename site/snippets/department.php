<?php if ($page->parents()->count() === 0): ?>
    Startseite
<?php else: ?>
    <?= $page->parents()->last()->title(); ?>
<?php endif; ?>