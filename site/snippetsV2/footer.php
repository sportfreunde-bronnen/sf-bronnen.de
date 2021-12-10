</main>

<footer class="footer bg-primary pt-4 pt-md-5">
    <div class="container pt-3 pt-md-0">
        <div class="row pb-3">
            <div class="col-12 col-lg-3 pb-3 pb-md-0 mb-4 widget-light">
                <h4 class="widget-title">Über uns</h4>
                <p class="fs-sm pb-2 pb-sm-3 text-white">Die Sportfreunde Bronnen sind ein über 600 Mitglieder fassender Verein bei Laupheim in Oberschwaben. Mit unseren Abteilungen Fitness/Gymnastik, Fußball und Aikido vermitteln wir Spaß und Freude am Sport und an der Bewegung</p><a class="btn-social bs-outline bs-facebook bs-light bs-lg me-2 mb-2" href="#"><i class="ai-facebook"></i></a><a class="btn-social bs-outline bs-instagram bs-light bs-lg me-2 mb-2" href="#"><i class="ai-instagram"></i></a><a class="btn-social bs-outline bs-github bs-light bs-lg me-2 mb-2" href="#"><i class="ai-github"></i></a>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3  pb-1 mb-4">
                <div class="widget widget-light">
                    <h4 class="widget-title">Kontakt</h4>
                    <div class="text-white fs-md">
                        <?= $site->homePage()->footerAddress()->kirbytext();?>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-md-4 col-lg-3  pb-1 mb-4">
                <div class="widget widget-light fs-md">
                    <h4 class="widget-title">Auf die Schnelle</h4>
                    <ul>
                    <?php foreach ($site->homePage()->children() as $item): ?>
                        <li class="my-0"><a class="widget-link" href="<?= $item->url();?>"><?php echo $item->title();?></a></li>
                    <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="col-md-4 col-lg-3 mb-4">
                <div class="widget widget-light fs-md">
                    <h4 class="widget-title">Freunde & Partner</h4>
                    <ul>
                    <?php foreach ($site->homePage()->links()->toStructure() as $link): ?>
                        <li class="my-0"><a title="Zur Webseite: <?= $link->name();?>" href="<?= $link->link();?>" class="widget-link"><?= $link->name();?></a></li>
                    <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <hr class="my-0 border-light">
        <div class="row align-items-center mt-3 py-4">
            <div class="col-12 mb-3">
                <p class="fs-sm mb-0 text-center text-white"><span class="me-1">© 2021 - #nurdiesfb</span></p>
            </div>
        </div>
    </div>
</footer>

<button class="btn btn-primary btn-sm is-sidebar sidebar-toggle" type="button" data-bs-toggle="offcanvas" data-bs-target="#blog-sidebar"><i class="ai-sidebar fs-base me-2"></i>Navigation</button>

<!-- Back to top button-->
<a class="btn-scroll-top" href="#top" data-scroll data-fixed-element><span class="btn-scroll-top-tooltip text-muted fs-sm me-2">Nach oben</span><i class="btn-scroll-top-icon ai-arrow-up"></i></a>

<!-- Vendor scrits: js libraries and plugins-->
<script src="/assetsV2/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="/assetsV2/vendor/simplebar/dist/simplebar.min.js"></script>
<script src="/assetsV2/vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js"></script>
<script src="/assetsV2/vendor/tiny-slider/dist/min/tiny-slider.js"></script>
<script src="/assetsV2/vendor/jarallax/dist/jarallax.min.js"></script>
<script src="/assetsV2/vendor/shufflejs/dist/shuffle.min.js"></script>
<script src="/assetsV2/vendor/lightgallery.js/dist/js/lightgallery.min.js"></script>
<script src="/assetsV2/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
<!-- Main theme script-->
<script src="/assetsV2/js/theme.min.js"></script>
</body>
</html>