<div class="my-3">
    <h2 class="h3 pt-2 pb-4"><?= $desc;?></h2>
    <div class="tns-carousel-wrapper gallery">
        <div class="tns-carousel-inner" data-carousel-options="{&quot;items&quot;: 2, &quot;controls&quot;: false, &quot;responsive&quot;: {&quot;0&quot;:{&quot;items&quot;:1, &quot;gutter&quot;: 20},&quot;500&quot;:{&quot;items&quot;:2, &quot;gutter&quot;: 20}, &quot;700&quot;:{&quot;items&quot;:2, &quot;gutter&quot;: 30}}}">
            <?php foreach ($images as $image):?>
                <?php echo $image->caption();?>
                <div><a class="gallery-item rounded-3" href="<?= $image->url();?>" data-sub-html="&lt;h6 class=&quot;fs-sm text-light&quot;&gt;<?= $image->caption();?>&lt;/h6&gt;"><img src="<?= $image->resize(428)->url();?>" alt="<?= $image->caption();?>"><span class="gallery-caption"><?= $image->caption();?></span></a></div>
            <?php endforeach; ?>
        </div>
    </div>
</div>