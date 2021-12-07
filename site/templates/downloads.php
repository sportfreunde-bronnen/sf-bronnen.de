<?php snippet('header'); ?>
<div class="page page--downloads">
    <?php snippet('h1'); ?>
    <div class="main-container container pl-sm-0 pr-sm-0 text-xs-center text-sm-center text-md-left">
        <h2 class="main-heading-1 text-spl-color text-weight-normal text-center text-lg-left"><?= $page->headline();?></h2>
        <div id="accordion-downloads">
            <?php if ($page->downloads()->toStructure()->count() > 0): ?>
            <?php $i = 0; foreach ($page->downloads()->toStructure() as $downloadBlock): $i++; ?>
                <div class="card flat">
                <!-- Card Heading Starts -->
                <div class="card-header flat">
                        <a data-toggle="collapse" data-target="#collapse<?= $i;?>" aria-expanded="true"
                           aria-controls="#collapse<?= $i;?>">
                            <h5 class="card-title">
                            <?= $downloadBlock->category();?>
                            <span class="fa fa-plus float-right"></span>
                            </h5>
                        </a>
                </div>
                <!-- Card Heading Ends -->
                <!-- Card Body Starts -->
                <div id="collapse<?= $i;?>" class="collapse" data-parent="#accordion-downloads">
                    <div class="card-body">
                        <ul class="list-group">
                            <?php foreach ($downloadBlock->downloads()->toFiles() as $download): ?>
                                <li class="list-group-item d-sm-flex justify-content-between align-items-center text-left">
                                    <a href="<?= $download->url();?>">
                                        <strong><?= $download->description();?></strong>
                                    </a>
                                    <span class="d-block d-sm-none"><?= strtoupper($download->extension());?>, <?= $page->convertFileSize($download->size());?></span>
                                    <span class="badge badge-primary badge-pill d-none d-sm-block"><?= strtoupper($download->extension());?>, <?= $page->convertFileSize($download->size());?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <!-- Card Body Ends -->
            </div>
            <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-secondary">
                    Aktuell sind keine Downloads vorhanden
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php snippet('footer'); ?><?php snippet('close'); ?>