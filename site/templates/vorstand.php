<?php snippet('header');?>
<section class="main-banner text-xs-center text-sm-center text-md-left">
    <div class="container text-lite-color pl-sm-0 pr-sm-0">
        <h1 class="text-weight-medium"><?= $page->title(); ?></h1>
    </div>
</section>
<div class="page">
    <div class="main-container container pl-sm-0 pr-sm-0 text-xs-center text-sm-center text-md-left">
        <h2 class="main-heading-1 text-spl-color text-weight-normal text-center-xs">Vorstandschaft</h2>
        <div class="row">
            <?php foreach ($page->personen()->toStructure() as $person):?>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="bio-box-1">
                    <div class="bio-data mt-3">
                        <h5 class="animation text-weight-normal mt-0"><?= $person->name();?></h5>
                        <p class="designation text-weight-light text-grey-color"><b><?= $person->funktion();?></b> (seit <?= $person->start();?>)</p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php snippet('footer');?>