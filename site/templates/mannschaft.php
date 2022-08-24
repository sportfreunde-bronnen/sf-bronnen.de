<?php snippet('header'); ?>
    <?php snippet('h1'); ?>
    <div class="page">
        <div class="main-container container pl-sm-0 pr-sm-0 text-xs-center text-sm-center text-md-left">
            <h2 class="main-heading-1 text-spl-color text-weight-normal text-center text-lg-left"><?= $page->headline();?></h2>
            <div class="row">
                <div class="col-12">
                    <?php
                    $fupaTab = $_GET['fupaTab'] ?? 'default';
                    $fupaData = $page->fupawidget();
                    if ($fupaTab !== 'default') {
                      echo str_replace('act:"team"', 'act:"' . $fupaTab . '"', $fupaData);
                    } else {
                      echo $fupaData;
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php snippet('footer'); ?>
<?php snippet('close'); ?>
