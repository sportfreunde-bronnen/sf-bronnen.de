<?php snippet('header');?>
<section class="my-5">
    <div class="container">
        <div class="col-12">
            <h2><?= $page->headline();?></h2>
            <hr/>
            <?= $page->text()->kirbytext();?>
        </div>
    </div>
</section>
<?php snippet('footer');?>
