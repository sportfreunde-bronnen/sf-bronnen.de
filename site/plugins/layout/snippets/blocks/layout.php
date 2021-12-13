<section id="<?= $block->CSSid() ?>" class="<?= $block->sectionPadding() == "none" ? "py-0" : "py-8 py-md-11" ?> <?= $site->backgroundColor($block->backgroundColor()) ?> <?= $block->CSSclass() ?>">
  <?php foreach ($block->layout()->toLayouts() as $layout): ?>
    <div class="<?= $layout->rowPadding() == "none" ? "py-0" : "py-4 py-md-8" ?> <?= $layout->CSSclass() ?>" id="<?= $layout->id() ?>">
      <div class="container">
        <div class="row <?= $layout->alignmentX()->toString() ?> <?= $layout->alignmentY()->toString() ?>">
          <?php foreach ($layout->columns() as $column): ?>
          <div class="col-12 col-md-<?= $column->span() ?> mb-6 mb-md-0">
            <div class="blocks">
              <?= $column->blocks() ?>
            </div>
          </div>
          <?php endforeach ?>        
        </div>
      </div>
    </div>
  <?php endforeach ?>
</section>