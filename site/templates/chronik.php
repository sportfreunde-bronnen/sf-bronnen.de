<?php snippet('header'); ?>
<div class="page page--chronik">
    <section class="main-banner text-xs-center text-sm-center text-md-left">
        <div class="container text-lite-color pl-sm-0 pr-sm-0">
            <h1 class="text-weight-medium"><?= $page->title(); ?></h1>
        </div>
    </section>
    <div class="main-container container pl-sm-0 pr-sm-0 text-xs-center text-sm-center text-md-left">
        <h2 class="main-heading-1 text-spl-color text-weight-normal text-center text-lg-left"><?= $page->headline();?></h2>
        Es fehlt etwas? Lass es uns wissen!
        <main role="main" class="container py-0 timeline">
            <!-- Line component -->
            <div class="timeline-line text-muted"></div>
            <?php foreach ($page->ereignisse()->toStructure()->sortBy('jahr', 'desc') as $ereignis): ?>
                <div class="timeline-separator">
                    <h3 class='version text-capitalize' id='unreleased'>
                        Jahr <?= $ereignis->jahr();?>
                    </h3>
                </div>
                <div class='row mr-0'>
                    <div class="card-group mx-2 mx-md-0 w-100">
                        <svg class='timeline-icon text-secondary pull-left d-none d-md-block' xmlns="http://www.w3.org/2000/svg" width="8" height="8" viewBox="0 0 8 8"><path d="M0 0v8l4-4-4-4z" style="fill:#007cb5;" transform="translate(2)" /></svg>
                        <div class="card">
                            <div class="card-body">
                                <?= $ereignis->ereignis()->kirbytext();?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </main>
    </div>
</div>
<?php snippet('footer'); ?><?php snippet('close'); ?>
