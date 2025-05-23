<div class="container pl-sm-0 pr-sm-0">
    <h5 class="sub-heading-2"><?= $desc;?></h5>
    <ul class="row list-unstyled shuffle" id="gallery-grid" style="position: relative; height: 1104px; transition: height 500ms ease-out;">
        <?php foreach ($images as $image):?>
            <li class="col-lg-4 col-md-6 col-sm-12 gallery-grid-item shuffle-item filtered" style="position: absolute; top: 0px; left: 0px; visibility: visible; transition: transform 500ms ease-out, opacity 500ms ease-out;">
                <div class="hover-content">
                    <img src="<?= $image->resize(400)->url();?>" alt="Gallery Image 1" class="img-fluid img-center animation-1">
                    <div class="overlay animation text-lite-color">
                        <h6 class="text-uppercase animation-1"><?= $desc;?></h6>
                        <p class="animation-1"><?= $image->description()->isEmpty() ? $image->caption() : $image->description();?></p>
                        <a href="<?= $image->url();?>" title="<?= $image->description()->isEmpty() ? $image->caption() : $image->description();?>" class="btn btn-1 animation-1"><i class="fa fa-search"></i></a>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>