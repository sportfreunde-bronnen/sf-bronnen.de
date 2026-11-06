<?php snippet('header'); ?>
<?php $downloadGroups = $page->downloads()->toStructure(); ?>
<div class="page page--downloads">
    <?php snippet('h1'); ?>
    <div class="main-container container pl-sm-0 pr-sm-0 text-xs-center text-sm-center text-md-left">
        <h2 class="main-heading-1 text-spl-color text-weight-normal text-center text-lg-left"><?= $page->headline();?></h2>
        <?php if ($downloadGroups->count() > 0): ?>
        <div class="download-groups" id="download-groups">
            <?php $i = 0; foreach ($downloadGroups as $downloadGroup): $i++; ?>
                <?php
                $files = $downloadGroup->downloads()->toFiles();
                $fileCount = $files->count();
                $isOpen = $i === 1;
                ?>
                <section class="download-group">
                    <h3 class="download-group__heading">
                        <button class="download-group__toggle<?= $isOpen ? '' : ' collapsed'; ?>" type="button"
                                data-toggle="collapse" data-target="#download-group-<?= $i; ?>"
                                aria-expanded="<?= $isOpen ? 'true' : 'false'; ?>"
                                aria-controls="download-group-<?= $i; ?>">
                            <span class="download-group__icon" aria-hidden="true"><i class="fa fa-folder-open-o"></i></span>
                            <span class="download-group__name"><?= $downloadGroup->category()->esc(); ?></span>
                            <span class="download-group__count"><?= $fileCount; ?> <?= $fileCount === 1 ? 'Datei' : 'Dateien'; ?></span>
                            <span class="download-group__chevron" aria-hidden="true"><i class="fa fa-angle-down"></i></span>
                        </button>
                    </h3>
                    <div id="download-group-<?= $i; ?>" class="collapse<?= $isOpen ? ' show' : ''; ?>" data-parent="#download-groups">
                        <ul class="download-list list-unstyled">
                            <?php foreach ($files as $download): ?>
                                <?php
                                $fileType = $page->fileTypeInfo($download->extension());
                                $title = $download->description()->isNotEmpty() ? $download->description()->value() : $download->name();
                                ?>
                                <li>
                                    <a class="download-item" href="<?= $download->url(); ?>">
                                        <span class="download-item__type download-item__type--<?= $fileType['type']; ?>" aria-hidden="true"><i class="fa <?= $fileType['icon']; ?>"></i></span>
                                        <span class="download-item__text">
                                            <span class="download-item__title"><?= esc($title); ?></span>
                                            <span class="download-item__meta"><?= strtoupper($download->extension()); ?> &middot; <?= $page->convertFileSize($download->size()); ?></span>
                                        </span>
                                        <span class="download-item__action">
                                            <i class="fa fa-download" aria-hidden="true"></i>
                                            <span class="download-item__label">Herunterladen</span>
                                        </span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                            <?php if ($fileCount === 0): ?>
                                <li class="download-list__empty">In dieser Kategorie sind aktuell keine Dateien vorhanden.</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </section>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
            <div class="download-empty">
                <i class="fa fa-folder-open-o" aria-hidden="true"></i>
                Aktuell sind keine Downloads vorhanden
            </div>
        <?php endif; ?>
    </div>
</div>
<?php snippet('footer'); ?><?php snippet('close'); ?>
