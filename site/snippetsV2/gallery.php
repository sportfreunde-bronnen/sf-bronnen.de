<div class="my-3">
    <h2 class="h3 pt-2 pb-4"><?= $desc;?></h2>
    <div class="tns-carousel-wrapper tns-controls-center gallery">
        <div class="tns-carousel-inner" data-carousel-options='{"nav": false, "responsive": {"0":{"items":1},"576":{"items":2, "gutter": 20},"1200":{"items":3, "gutter": 30}}}'>
            <?php foreach ($images as $image):?>
                <div>
                    <a class="gallery-item rounded-3" href="<?= $image->url();?>" data-sub-html="&lt;h6 class=&quot;fs-sm text-light&quot;&gt;<?= $image->caption();?>&lt;/h6&gt;">
                        <img src="<?= $image->resize(428)->url();?>" alt="<?= $image->caption();?>"/>
                        <span class="gallery-caption"><?= $image->caption();?></span>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>