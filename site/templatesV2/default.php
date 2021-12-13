<?php snippet('header'); ?>
<section>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3><?= $page->title();?></h3>
            </div>
        </div>
    </div>
</section>

<?php foreach ($page->wysiwyg()->toBlocks() as $block): ?>
    <div id="<?= $block->id() ?>" class="block block-type-<?= $block->type() ?>">
        <?= $block ?>
    </div>
<?php endforeach ?>

<?php snippet('footer'); ?>
