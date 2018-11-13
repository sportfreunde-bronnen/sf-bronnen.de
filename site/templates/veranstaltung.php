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
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <td class="font-weight-bold">Wann?</td>
                            <td>
                                <?= $page->datum()->toGermanDate();?>
                                <?php if ($page->datumbis()->isNotEmpty()): ?> - <?= $page->datumbis()->toGermanDate();?><?endif; ?>
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
                        <div class="box-3 animation text-xs-center text-sm-center text-md-left mt-0">
                            <?php if ($related->vimage()->isNotEmpty()): ?>
                                <p>
                                    <img src="<?= $related->vimage()->toFile()->resize(350)->url();?>" alt="Blog Image" class="img-fluid img-center-xs img-center-sm">
                                </p>
                            <?php endif; ?>
                            <div class="inner">
                                <p class="date-meta text-grey-color text-uppercase">
                                    <span class="badge p-1 badge-secondary">
                                        <i class="fa fa-calendar"></i>&nbsp; <?= $related->datum()->toGermanDate(); ?>
                                    </span>
                                </p>
                                <h6 class="sub-heading-1 tiny text-weight-medium text-xs-center text-sm-center text-md-left">
                                    <?= $related->title();?>
                                </h6>
                                <p class="text-grey-color">
                                    <?= $related->shorttitle();?>
                                </p>
                                <p class="text-md-right">
                                    <a href="<?= $related->url();?>" class="more-link animation">Mehr erfahren</a>
                                </p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php snippet('footer'); ?><?php snippet('close'); ?>