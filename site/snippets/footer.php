<section class="footer-top">
    <div class="container pl-sm-0 pr-sm-0 text-xs-center text-sm-center text-md-left text-lite-color">
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="foot-info">
                    <p class="text-weight-light">
                        <em>Die Sportfreunde Bronnen sind ein über 600 Mitglieder fassender Verein bei Laupheim in Oberschwaben. Mit unseren Abteilungen Fitness/Gymnastik, Fußball und Aikido vermitteln wir Spaß und Freude am Sport und an der Bewegung.</em>
                    </p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <h5>Kontakt</h5>
                <ul class="list-unstyled foot-address-list text-weight-light">
                    <li class="clearfix">
                        <span>
                            <strong>Sportfreunde Bronnen 1949 e.V.</strong></br>
                            Färbergässle 2/1<br/>
                            88471 Laupheim<br/>
                            info@sf-bronnen.de
                        </span>
                    </li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <h5>Auf die Schnelle</h5>
                <ul class="list-unstyled foot-list-style-1 text-weight-light">
                    <?php foreach ($site->homePage()->children() as $item): ?>
                        <li><a href="<?= $item->url();?>"><?php echo $item->title();?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <h5>Freunde & Partner</h5>
                <ul class="list-unstyled foot-list-style-1 text-weight-light">
                    <?php foreach ($site->homePage()->links()->toStructure() as $link): ?>
                        <li><a title="Zur Webseite: <?= $link->name;?>" href="<?= $link->link;?>"><?= $link->name;?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</section>
<footer class="copyright">
    <div class="container pl-sm-0 pr-sm-0 text-center text-lite-color">
        <em class="text-weight-light">&copy; <?= date('Y');?> - Sportfreunde Bronnen 1949 e.V. | Made with <a href="https://getkirby.com/">Kirby</a> and <b class="red">♥</b></em>
    </div>
</footer>
<script src="/assets/js/jquery-3.3.1.min.js"></script>
<script src="/assets/js/popper.min.js"></script>
<script src="/assets/js/bootstrap.js"></script>
<script src="/assets/js/plugins/backstretch/jquery.backstretch.min.js"></script>
<script src="/assets/js/plugins/shuffle/jquery.shuffle.modernizr.min.js"></script>
<script src="/assets/js/plugins/owl-carousel2/owl.carousel.min.js"></script>
<script src="/assets/js/plugins/magnific-popup/jquery.magnific-popup.min.js"></script>
<script src="/assets/js/custom.js"></script>