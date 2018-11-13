<?php snippet('header'); ?>
<?php snippet('h1');?>
<div class="page">
    <div class="main-container container pl-sm-0 pr-sm-0">
        <h2 class="main-heading-1 text-spl-color text-weight-normal text-uppercase"><?= $page->headline();?></h2>
        <div class="row">
            <div class="col-12<?= ($page->images()->count() > 0) ? ' col-lg-8' : ''; ?>">
                <?= $page->text()->kirbytext();?>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Zeitraum</th>
                        <th>Trainer</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($page->coaches()->toStructure() as $coach): ?>
                        <tr>
                            <td class="text-weight-bold"><?= $coach->zeitraum();?></td>
                            <td><?= $coach->name();?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php snippet('footer'); ?><?php snippet('close'); ?>
