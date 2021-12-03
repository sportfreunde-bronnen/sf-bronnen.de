<?php snippet('header');?>
<section class="my-5">
    <div class="container">
        <div class="row">
            <h2>Unser Team</h2>
            <?php foreach ($page->personen()->toStructure() as $person):?>
                <div class="col-12 col-md-4 p-2">
                    <div class="card border-0 shadow card-hover">
                        <div class="card-body">
                            <h5 class="card-title text-primary"><?= $person->name();?></h5>
                            <p class="card-text fs-sm"><b><?= $person->funktion();?></b> (seit <?= $person->start();?>)</p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php snippet('footer');?>
