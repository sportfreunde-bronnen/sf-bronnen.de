<?php if ($page->people()->toStructure()->isNotEmpty()): ?>
<h3 class="main-heading-1 text-spl-color text-weight-normal text-center-xs mt-5">Verantwortliche</h3>
<div class="row">
    <?php foreach ($page->people()->toStructure() as $person): ?>
        <div class="col-12 col-md-6 mb-4">
            <div class="person-card person-card--contact">
                <h5 class="person-card__name"><?= $person->name();?></h5>
                <p class="person-card__role"><?= $person->aufgabe();?></p>
                <?php if (!$person->address()->isEmpty() || !$person->email()->isEmpty() || !$person->phone()->isEmpty()): ?>
                    <ul class="person-card__contact list-unstyled">
                        <?php if (!$person->address()->isEmpty()): ?>
                            <li><i class="fa fa-map-marker" aria-hidden="true"></i><span><?= $person->address();?></span></li>
                        <?php endif; ?>
                        <?php if (!$person->email()->isEmpty()): ?>
                            <li><i class="fa fa-envelope-o" aria-hidden="true"></i><a href="mailto:<?= $person->email();?>"><?= $person->email();?></a></li>
                        <?php endif; ?>
                        <?php if (!$person->phone()->isEmpty()): ?>
                            <li><i class="fa fa-phone" aria-hidden="true"></i><span><?= $person->phone();?></span></li>
                        <?php endif; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php endif;?>
