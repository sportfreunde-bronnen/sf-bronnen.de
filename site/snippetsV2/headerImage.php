<?php if (!$page->isHomePage()):?>
    <?php if ($page->disableHeader()->toBool() === false): ?>
        <section class="d-flex align-items-center justify-content-center jarallax bg-gradient vh-40" data-jarallax="" data-speed="0.25" style="background-image: none;">
            <span class="position-absolute top-0 start-0 w-100 h-100 bg-gradient opacity-80"></span>
            <?php if($page->headerStyle() == 'slantBottomRight'): ?>
                <div class="shape shape-bottom shape-slant bg-body bg-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 3000 260">
                        <polygon fill="currentColor" points="0,257 0,260 3000,260 3000,0"></polygon>
                    </svg>
                </div>
            <?php elseif ($page->headerStyle() == 'slantBottomLeft'): ?>
                <div class="shape shape-bottom shape-slant bg-body bg-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 3000 260">
                        <polygon fill="currentColor" points="0,0 0,260 3000,260 3000,255"></polygon>
                    </svg>
                </div>
            <?php elseif ($page->headerStyle() == 'curveBottomLeft'): ?>
                <div class="shape shape-bottom shape-curve-side bg-body bg-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 3000 250">
                        <path fill="currentColor" d="M0,0l0,250h3000v-51c-572.7,34.3-1125.3,34.3-1657.8,0C809.7,164.8,362.3,98.4,0,0z"></path>
                    </svg>
                </div>
            <?php elseif ($page->headerStyle() == 'curveBottomCenter'): ?>
                <div class="shape shape-bottom shape-curve bg-body bg-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 3000 185.4">
                        <path fill="currentColor" d="M3000,0v185.4H0V0c496.4,115.6,996.4,173.4,1500,173.4S2503.6,115.6,3000,0z"></path>
                    </svg>
                </div>
            <?php else: ?>
                <div class="shape shape-bottom bg-body bg-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 3000 260">
                        <polygon fill="currentColor" points="0,257 0,260 3000,260 3000,0"></polygon>
                    </svg>
                </div>
            <?php endif; ?>
            <div class="d-flex flex-column container justify-content-start position-relative zindex-5 mt-5 mt-lg-4 mt-xl-2">
                <div class="row">
                    <div class="col-12<?= $page->template() == 'bericht' ? ' text-start' : ' text-center';?>">
                        <h1 class="text-light display-4"><?= $page->title();?></h1>
                        <?php if ($page->template() == 'bericht' && $page->teaserImage()->toFile() !== Null): ?>
                            <p class="text-light"><?= $page->teaserImage()->toFile()->caption();?></p>
                        <?php else: ?>
                            <p class="text-light">Test 123</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php
            $headerImage = ($page->headerImage()->toFile() !== null && $page->individualHeader()->toBool() === true) ? $page->headerImage()->toFile() : null;
            $headerImage = is_null($headerImage) && $page->template() == 'bericht' ? $page->teaserImage()->toFile() : $headerImage;
            ?>
            <?php if ($headerImage !== null): ?>
                <div id="jarallax-container-0" style="position: absolute; top: 0px; left: 0px; width: 100%; height: 100%; overflow: hidden; z-index: -100;">
                    <div class="jarallax-img" style="background-image: url(<?= $headerImage->quality(50)->url();?>); object-fit: contain; object-position: 50% 50%; max-width: none; position: fixed; top: 0px; left: 0px; width: 1647px; height: 1118.75px; overflow: hidden; pointer-events: none; transform-style: preserve-3d; backface-visibility: hidden; will-change: transform, opacity; margin-top: 105.125px; transform: translate3d(0px, -85.375px, 0px);" data-jarallax-original-styles="background-image: url('<?= $headerImage->quality(50)->url();?>');"></div>
                </div>
            <?php endif; ?>
        </section>
    <?php endif; ?>
<?php endif; ?>