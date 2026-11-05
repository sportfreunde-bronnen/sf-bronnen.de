<?php snippet('header'); ?>
    <?php snippet('h1');?>
    <div class="page">
        <div class="main-container container pl-sm-0 pr-sm-0">
            <div class="row">
                <div class="col-12 col-lg-8 veranstaltung--container">
                    <?php if ($page->vimage()->isNotEmpty() && $page->imageposition() == 'top'): ?>
                        <img class="img-fluid pt-3" src="<?= $page->vimage()->toFile()->crop(750, 200)->url();?>"/><hr/>
                    <?php endif; ?>
                    <?= $page->text()->kirbytext();?>
                    <?php if ($page->vimage()->isNotEmpty() && $page->imageposition() == 'bottom'): ?>
                        <hr/><img class="img-fluid" src="<?= $page->vimage()->toFile()->crop(750, 200)->url();?>"/>
                    <?php endif; ?>
                </div>
                <div class="col-12 col-lg-4">
                    <h5 class="sub-heading-2">Kurz und knapp:</h5>
                    <table class="table table-bordered table--facts">
                        <tbody>
                        <tr>
                            <td class="font-weight-bold">Wann?</td>
                            <td>
                                <?= $page->datum()->toDate('d.m.Y H:i');?>
                                <?php if ($page->datumbis()->isNotEmpty()): ?> - <?= $page->datumbis()->toDate('d.m.Y H:i');?><?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Wo?</td>
                            <td><?= $page->ort();?></td>
                        </tr>
                        <?php if ($page->doc()->isNotEmpty()): ?>
                            <tr>
                                <td class="font-weight-bold"><?= $page->documentdescription();?></td>
                                <td><a href="<?= $page->doc()->toFile()->url();?>">Download</a></td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                    <?php if ($page->related()->isNotEmpty()): $related = $page->related()->toPage(); ?>
                        <h5 class="sub-heading-2 mt-4">Auch interessant:</h5>
                        <div class="row">
                            <div class="col-12 col-md-6 col-lg-12">
                                <article class="post-card post-card--compact">
                                    <?php if ($related->vimage()->isNotEmpty()): ?>
                                        <a class="post-card-media" href="<?= $related->url();?>" tabindex="-1" aria-hidden="true">
                                            <img src="<?= $related->vimage()->toFile()->resize(350)->url();?>" alt="Veranstaltungsbild - <?= $related->title();?> - Sportfreunde Bronnen" loading="lazy">
                                        </a>
                                    <?php endif; ?>
                                    <div class="post-card-body">
                                        <div class="post-meta">
                                            <time datetime="<?= $related->datum()->toDate('Y-m-d'); ?>"><i class="fa fa-calendar-o"></i><?= $related->datum()->toDate('d.m.Y'); ?></time>
                                        </div>
                                        <h6 class="post-card-title">
                                            <a href="<?= $related->url();?>"><?= $related->title();?></a>
                                        </h6>
                                        <p class="post-card-excerpt"><?= $related->shorttitle();?></p>
                                        <div class="post-card-footer">
                                            <a href="<?= $related->url();?>" class="post-more">Mehr erfahren <i class="fa fa-long-arrow-right"></i></a>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php snippet('footer'); ?><?php snippet('close'); ?>