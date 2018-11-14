<nav id="nav" class="main-menu navbar navbar-expand-md rounded-0">
    <div class="container pl-sm-0 pr-sm-0">
        <div class="col-12 col-md-10 pl-md-0">
            <button class="navbar-toggler ml-auto pl-0 collapsed" type="button" data-toggle="collapse" data-target=".navbar-cat-collapse" aria-controls=".navbar-cat-collapse" aria-expanded="false" aria-badge="Toggle navigation">
                <span class="navbar-toggler-icon fa fa-bars"></span>
            </button>
            <div class="collapse navbar-collapse navbar-cat-collapse animation">
                <ul class="nav navbar-nav text-weight-bold">
                    <?php foreach($pages->visible() as $page): ?>
                        <?php if ($page->children()->visible()->count()): ?>
                            <li class="nav-item dropdown<?= ($page->isOpen()) ? ' active show' : '';?>">
                                <a href="<?= $page->url();?>" class="nav-link dropdown-toggle" data-toggle="dropdown"><?= $page->title();?> <i class="fa fa-angle-down"></i></a>
                                <?php if ($page->hasChildren()): ?>
                                    <div class="dropdown-menu rounded-0<?= ($page->isOpen()) ? ' show' : '';?>">
                                        <?php foreach ($page->children()->visible() as $subPage): ?>
                                            <?php if ($subPage->hasChildren()): ?>
                                                <a class="dropdown-item sub <?= ($subPage->isOpen()) ? ' active' : '';?>"><?= $subPage->title();?></a>
                                                <?php if ($subPage->title() == 'Veranstaltungen'):?>
                                                    <?php foreach ($subPage->children()->visible()->sortBy('datum') as $subSubPage): ?>
                                                        <a class="dropdown-item<?= ($subSubPage->isOpen()) ? ' active' : '';?>" href="<?= $subSubPage->url();?>"><?= $subSubPage->title();?></a>
                                                    <?php endforeach;?>
                                                <?php else: ?>
                                                    <?php foreach ($subPage->children()->visible() as $subSubPage): ?>
                                                        <a class="dropdown-item<?= ($subSubPage->isOpen()) ? ' active' : '';?>" href="<?= $subSubPage->url();?>"><?= $subSubPage->title();?></a>
                                                    <?php endforeach;?>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <a class="dropdown-item<?= ($subPage->isOpen()) ? ' active' : '';?>" href="<?= $subPage->url();?>"><?= $subPage->title();?></a>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </li>
                        <?php else: ?>
                            <li class="nav-item<?= ($page->isOpen()) ? ' active' : '';?>"><a href="<?= $page->url();?>" class="nav-link"><?= $page->title();?></a></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
            </div>
        </div>
        <div id="departmentViewScroll" class="d-none d-md-block invisible">
            <?php snippet('department');?>
            <img src="/assets/images/sfb/skill-sfb-108.png" class=""/>
        </div>
    </div>
</nav>