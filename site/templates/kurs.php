<?php snippet('header'); ?>
    <?php snippet('h1');?>
    <div class="page">
        <div class="main-container container pl-sm-0 pr-sm-0">
            <h2 class="main-heading-1 text-spl-color text-weight-normal text-uppercase"><?= $page->headline();?></h2>
            <div class="row">
                <div class="col-12<?= ($page->images()->count() > 0) ? ' col-lg-8' : ''; ?>">
                    <?= $page->text()->kirbytext();?>
                    <?php if ($page->hinweis()->isNotEmpty()): ?>
                        <div class="alert alert-info">
                            <?= $page->hinweis();?>
                        </div>
                    <?php endif; ?>
                    <table class="table table-striped table-kurs">
                        <tbody>
                        <tr>
                            <td class="text-weight-bold">Übungsleiter-/in</td>
                            <td><?= $page->trainer();?></td>
                        </tr>
                        <tr>
                            <td class="text-weight-bold">Trainingsort</td>
                            <td><?= $page->ort()->kirbytext();?></td>
                        </tr>
                        <tr>
                            <td class="text-weight-bold">Trainingszeiten</td>
                            <td><?= $page->zeiten()->kirbytext();?></td>
                        </tr>
                        <tr>
                            <td class="text-weight-bold">Anmeldung/Infos</td>
                            <td><?= $page->anmeldung()->kirbytext();?></td>
                        </tr>
                        <?php if ($page->kosten()->isNotEmpty()): ?>
                            <tr>
                                <td class="text-weight-bold">Kosten</td>
                                <td><?= $page->kosten()->kirbytext();?></td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-12 text-center-md<?= ($page->images()->count() > 0) ? ' col-lg-4' : ''; ?>">
                    <?php if ($page->images()->count() > 0): ?>
                        <?php foreach ($page->images()->limit(2) as $image): ?>
                            <img class="rounded mb-1 img-fluid" src="<?= $image->resize(400)->url();?>"/>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="col-12 mt-lg-4">
                    <hr/>
                    <?php $zitat = $page->parents()->last()->zitate()->toStructure()->shuffle()->first();?>
                    <blockquote class="blockquote text-center">
                        <p class="mb-0"><?= $zitat->text();?></p>
                        <footer class="blockquote-footer"><?= $zitat->person();?></footer>
                    </blockquote>
                </div>
            </div>
        </div>
    </div>
<?php snippet('footer'); ?>
