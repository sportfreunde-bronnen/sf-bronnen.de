<section class="jarallax bg-gradient pt-5 pt-md-7 pb-7" data-jarallax="" data-speed="0.25" style="background-image: none;">
    <span class="position-absolute top-0 start-0 w-100 h-100 bg-gradient opacity-80"></span>
    <?php if($page->headerStyle() == 'slantBottomRight'): ?>
    <div class="shape shape-bottom shape-slant bg-body">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 3000 260">
            <polygon fill="currentColor" points="0,257 0,260 3000,260 3000,0"></polygon>
        </svg>
    </div>
    <?php elseif ($page->headerStyle() == 'slantBottomLeft'): ?>
    <div class="shape shape-bottom shape-slant bg-body">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 3000 260">
            <polygon fill="currentColor" points="0,0 0,260 3000,260 3000,255"></polygon>
        </svg>
    </div>
    <?php elseif ($page->headerStyle() == 'curveBottomLeft'): ?>
    <div class="shape shape-bottom shape-curve-side bg-body">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 3000 250">
            <path fill="currentColor" d="M0,0l0,250h3000v-51c-572.7,34.3-1125.3,34.3-1657.8,0C809.7,164.8,362.3,98.4,0,0z"></path>
        </svg>
    </div>
    <?php elseif ($page->headerStyle() == 'curveBottomCenter'): ?>
    <div class="shape shape-bottom shape-curve bg-body">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 3000 185.4">
            <path fill="currentColor" d="M3000,0v185.4H0V0c496.4,115.6,996.4,173.4,1500,173.4S2503.6,115.6,3000,0z"></path>
        </svg>
    </div>
    <?php endif; ?>
    <div class="container position-relative zindex-5 pt-0 pt-md-3 pt-lg-3 pb-0 pb-md-2 pb-lg-5">
        <div class="row pb-0 pb-md-2 pb-lg-5">
            <div class="col-12 pt-5 pt-md-3 pt-lg-5 text-center">
                <h1 class="text-light"><?= $page->title();?></h1>
                <p class="text-light">Test 123</p>
            </div>
        </div>
    </div>
    <div id="jarallax-container-0" style="position: absolute; top: 0px; left: 0px; width: 100%; height: 100%; overflow: hidden; z-index: -100;">
        <div class="jarallax-img" style="background-image: url(<?= $page->headerImage()->toFile()->quality(50)->url();?>); object-fit: contain; object-position: 50% 50%; max-width: none; position: fixed; top: 0px; left: 0px; width: 1647px; height: 1118.75px; overflow: hidden; pointer-events: none; transform-style: preserve-3d; backface-visibility: hidden; will-change: transform, opacity; margin-top: 105.125px; transform: translate3d(0px, -85.375px, 0px);" data-jarallax-original-styles="background-image: url('<?= $page->headerImage()->toFile()->quality(50)->url();?>');"></div>
    </div>
</section>