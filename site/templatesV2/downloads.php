<?php snippet('header');?>

<section class="my-5">
    <div class="container">
        <h2 class="text-center text-lg-start mb-5"><?= $page->headline();?></h2>
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="accordion" id="downloads">
                    <?php $i = 0; foreach ($page->downloads()->toStructure() as $downloadBlock): $i++; ?>
                    <div class="accordion-item bg-white shadow">
                        <h2 class="accordion-header" id="downloads-heading-<?= $i;?>">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#downloads-content-<?= $i;?>" aria-expanded="false" aria-controls="downloads-content-<?= $i;?>"><?= $downloadBlock->category();?></button>
                        </h2>
                        <div class="accordion-collapse collapse" id="downloads-content-<?= $i;?>" aria-labelledby="downloads-heading-<?= $i;?>" data-bs-parent="#downloads">
                            <div class="accordion-body">
                                <ul class="list-group">
                                    <?php $ii = 0; foreach ($downloadBlock->downloads()->toFiles() as $download): $ii++; ?>
                                    <?php if ($ii > 1): ?>
                                    <hr class="my-0"/>
                                    <?php endif; ?>
                                    <li class="list-group-item d-flex flex-column flex-md-row justify-content-between align-items-start rounded-0 px-3 border-0 bg-secondary">
                                        <span>
                                            <i class="ai-link"></i>
                                            <a href="<?= $download->url();?>">
                                                <b><?= $download->description();?></b>
                                            </a>
                                        </span>
                                        <span class="badge rounded-pill bg-primary py-1 px-2 d-none d-md-block"><?= strtoupper($download->extension());?>, <?= $download->niceSize();?></span>
                                    </li>
                                    <?php endforeach;?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php snippet('footer');?>
