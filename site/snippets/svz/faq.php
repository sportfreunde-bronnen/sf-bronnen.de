<?php
/**
 * "FAQs" section of the SVZ page: questions and answers as accordion.
 *
 * The entries are maintained in the Panel (field "faqs"). Without JavaScript all answers are displayed.
 *
 * @var SvzPage $page
 */

$faqs = $page->faqs()->toStructure();
?>
<div class="svz-section-head svz-reveal">
    <p class="svz-eyebrow">FAQs</p>
    <h2 class="svz-title">Häufige Fragen zur Vermietung</h2>
    <div class="svz-lead">
        <?php if ($page->faqsText()->isNotEmpty()): ?>
            <?= $page->faqsText()->kirbytext();?>
        <?php else: ?>
            <p>Hier beantworten wir die finalen Fragen zur Vermietung des SVZ.</p>
        <?php endif; ?>
    </div>
</div>

<ul class="svz-faq" data-svz-faq>
    <?php foreach ($faqs as $index => $faq): ?>
        <?php $id = 'svz-faq-' . ($index + 1); ?>
        <li class="svz-faq__item svz-reveal">
            <h3 class="svz-faq__heading">
                <button type="button" class="svz-faq__trigger" id="<?= $id;?>-question" aria-expanded="false" aria-controls="<?= $id;?>-answer" data-svz-faq-trigger>
                    <span class="svz-faq__question"><?= $faq->question()->esc();?></span>
                    <span class="svz-faq__icon" aria-hidden="true"></span>
                </button>
            </h3>
            <div class="svz-faq__panel" id="<?= $id;?>-answer" role="region" aria-labelledby="<?= $id;?>-question">
                <div class="svz-faq__answer">
                    <div class="svz-faq__content"><?= $faq->answer()->kirbytext();?></div>
                </div>
            </div>
        </li>
    <?php endforeach; ?>
</ul>

<div class="svz-notice svz-faq-note svz-reveal">
    <i class="fa fa-question-circle" aria-hidden="true"></i>
    <p>Ihre Frage ist nicht dabei? Sprechen Sie uns einfach an, z.B. über unser <a href="<?= esc(SvzDomain::contactUrl(), 'attr');?>">Kontaktformular</a>.</p>
</div>
