<?php snippet('header');?>
<section class="mt-5">
    <div class="container">
        <h2 class="text-center text-lg-start mb-5"><?= $page->headline();?></h2>
        <div class="row">
        <?php $box = false; $last = ''; foreach ($page->sponsoren()->toStructure()->sortBy('name', 'asc') as $sponsor): $current = strtoupper(substr($sponsor->name(), 0, 1)); ?>
        <?php if ($last != $current): ?>
        <?php if ($box): ?>
        </ul></div>
        <?php endif; ?>
        <div class="col-12 col-md-6 col-lg-4 col-xxl-3 mb-4"><ul class="list-group list-group-flush">
                <li class="list-group-item active font-weight-bold"><b><?= strtoupper($current);?></b></li>
            <?php endif; ?>
            <?php if ($sponsor->webseite()->isNotEmpty()):?>
            <li class="list-group-item">
                <a href="<?= $sponsor->webseite();?>" alt="Zur Webseite: <?= $sponsor->name();?> <?= $sponsor->ort();?>" title="Zur Webseite: <?= $sponsor->name();?> <?= $sponsor->ort();?>"><?= $sponsor->name();?></a><br/><small><i><?= $sponsor->ort();?></i></small><br/>
            </li>
            <?php else: ?>
                <li class="list-group-item">
                <?= $sponsor->name();?><br/><small><i><?= $sponsor->ort();?></i></small><br/>
                </li>
            <?php endif; ?>
            <?php $last = $current; $box = true; endforeach; ?>
        </div>
    </div>
</section>

<section>
    <div class="jarallax bg-primary py-7" data-jarallax data-speed="0.3">
        <span class="position-absolute top-0 start-0 w-100 h-100 bg-primary" style="opacity: .80;"></span>
        <div class="jarallax-img" style="background-image: url('<?= $page->image()->url();?>');"></div>
        <div class="container position-relative zindex-2 py-5 py-sm-7 text-center">
            <h2 class="h1 text-light mb-0">Vielen Dank an unsere treuen Sponsoren <i class="ai-heart"></i></h2>
        </div>
    </div>
</section>
<?php snippet('footer');?>
