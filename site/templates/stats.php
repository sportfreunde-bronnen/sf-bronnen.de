<?php snippet('header'); ?>
<section class="main-banner text-xs-center text-sm-center text-md-left">
    <div class="container text-lite-color pl-sm-0 pr-sm-0">
        <h1 class="text-weight-medium"><?= $page->title();?></h1>
    </div>
</section>
<div class="page">
    <div class="main-container container pl-sm-0 pr-sm-0">
        <h2 class="main-heading-1 text-spl-color text-weight-normal text-center-xs"><?= $page->headline();?></h2>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Datum</th>
                <th>Spieler</th>
                <th>Spiel</th>
                <th>Mannschaft</th>
                <th>&nbsp;</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($page->children() as $statsPage): ?>
                <tr>
                    <td class="text-weight-bold"><?= date('d.m.Y', $statsPage->date());?></td>
                    <td><?= $statsPage->spieler();?></td>
                    <td><?= $statsPage->gegner();?></td>
                    <td><?= ucfirst($statsPage->category());?></td>
                    <td><a href="<?= $statsPage->url();?>" class="btn btn-1">Einsehen</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php snippet('footer'); ?><?php snippet('close'); ?>
