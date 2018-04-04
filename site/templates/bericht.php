<?php snippet('header'); ?>
<div class="page">
    <div class="main-container container pl-sm-0 pr-sm-0 page--bericht">
        <div class="row">
            <div class="col-md-8 col-sm-12">
                <div class="blog-post single text-xs-center">
                    <div class="box-3">
                        <div class="inner">
                            <p class="date-meta text-grey-color text-uppercase">
                                <span class="badge badge-secondary">
                                    <i class="fa fa-calendar"></i>&nbsp; <?= date('d.m.Y', strtotime($page->datum()));?>
                                </span>&nbsp
                            </p>
                            <h1 class="sub--1 tiny text-medium h3">
                                <?= $page->title();?>
                            </h1>
                            <div class="blog-post-content">
                                <?= $page->text()->kirbytext(); ?>
                            </div>
                            <div class="clearfix">
                                <ul class="list-unstyled list-inline blog-post-tags float-md-left animation">
                                    <li class="list-inline-item"><h6>Tags: </h6></li>
                                    <?php foreach (explode(',', $page->tags()) as $tag): ?>
                                        <li class="list-inline-item"><i class="fa fa-tag"></i> <a href="#"><?= $tag;?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-100 d-xs-block d-sm-block d-md-none">
                <p class="spacer-small"></p>
            </div>
            <div class="col-md-4 col-sm-12">
                <h5 class="sub-heading-2 text-xs-center">Letzte Artikel</h5><br>
                <ul class="list-unstyled list-post-1">
                    <li>
                        <h6 class="sub-heading-1">Technology Solutions</h6>
                        <div class="row">
                            <div class="col-12">
                                <p>There are many variations of passages of available ....</p>
                                <a href="#" class="more-link animation">More..</a>
                            </div>
                        </div>
                    </li>
                    <li>
                        <h6 class="sub-heading-1">Investment Management</h6>
                        <div class="row">
                            <div class="col-12">
                                <p>There are many variations of passages of available ....</p>
                                <a href="#" class="more-link animation">More..</a>
                            </div>
                        </div>
                    </li>
                    <li>
                        <h6 class="sub-heading-1">Retirement Planning</h6>
                        <div class="row">
                            <div class="col-12">
                                <p>There are many variations of passages of available ....</p>
                                <a href="#" class="more-link animation">More..</a>
                            </div>
                        </div>
                    </li>
                </ul>
                <div class="spacer-medium"></div>
            </div>
        </div>
    </div>
</div>
<?php snippet('footer'); ?>