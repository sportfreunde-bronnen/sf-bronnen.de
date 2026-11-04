<?php
/**
 * Availability calendar of the SVZ.
 *
 * Renders SvzPage::CALENDAR_MONTHS months. Without JavaScript all months are listed one below the other,
 * with JavaScript (svz.js) one month is shown at a time and can be browsed.
 *
 * @var array|null $bookings booked days ('Y-m-d' => row) or null if the bookings are not available
 */

$monthNames = ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'];
$weekdays = ['Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So'];
$weekdaysLong = ['Mo' => 'Montag', 'Di' => 'Dienstag', 'Mi' => 'Mittwoch', 'Do' => 'Donnerstag', 'Fr' => 'Freitag', 'Sa' => 'Samstag', 'So' => 'Sonntag'];

$bookings ??= null;
$available = is_array($bookings);

$today = new DateTimeImmutable('today');
$firstMonth = $today->modify('first day of this month');
$monthCount = SvzPage::CALENDAR_MONTHS;

$requestedMonth = (int)get('calMonth');
$requestedYear = (int)get('calYear');

$months = [];
$activeIndex = 0;

for ($i = 0; $i < $monthCount; $i++) {
    $first = $firstMonth->modify('+' . $i . ' months');
    $months[$i] = [
        'first' => $first,
        'title' => $monthNames[(int)$first->format('n') - 1] . ' ' . $first->format('Y'),
    ];

    if ((int)$first->format('n') === $requestedMonth && (int)$first->format('Y') === $requestedYear) {
        $activeIndex = $i;
    }
}

// next free saturdays as quick overview
$freeSaturdays = [];

if ($available) {
    $cursor = $today;
    $end = $firstMonth->modify('+' . $monthCount . ' months');

    while ($cursor < $end && count($freeSaturdays) < 4) {
        if ($cursor->format('N') === '6' && !isset($bookings[$cursor->format('Y-m-d')])) {
            $freeSaturdays[] = $cursor;
        }
        $cursor = $cursor->modify('+1 day');
    }
}
?>
<div class="svz-cal" data-svz-calendar data-active="<?= $activeIndex;?>">

    <?php if (!$available): ?>
        <div class="svz-notice svz-notice--warning" role="status">
            <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
            <span>Der Belegungskalender ist gerade nicht erreichbar. Bitte fragen Sie Ihren Wunschtermin direkt über unser <a href="/home/kontakt?rec=svz">Kontaktformular</a> an.</span>
        </div>
    <?php endif; ?>

    <ul class="svz-cal__legend" aria-label="Legende">
        <li><span class="svz-dot svz-dot--free"></span> Frei</li>
        <li><span class="svz-dot svz-dot--booked"></span> Belegt</li>
        <li><span class="svz-dot svz-dot--today"></span> Heute</li>
    </ul>

    <div class="svz-cal__bar" data-svz-cal-bar>
        <div class="svz-cal__pager">
            <button type="button" class="svz-cal__nav" data-svz-cal-prev aria-label="Vorheriger Monat"><i class="fa fa-chevron-left" aria-hidden="true"></i></button>
            <div class="svz-cal__current" data-svz-cal-title aria-live="polite"><?= esc($months[$activeIndex]['title']);?></div>
            <button type="button" class="svz-cal__nav" data-svz-cal-next aria-label="Nächster Monat"><i class="fa fa-chevron-right" aria-hidden="true"></i></button>
        </div>
        <div class="svz-cal__tools">
            <select class="svz-cal__select" data-svz-cal-select aria-label="Monat auswählen">
                <?php foreach ($months as $index => $month): ?>
                    <option value="<?= $index;?>"<?= $index === $activeIndex ? ' selected' : '';?>><?= esc($month['title']);?></option>
                <?php endforeach; ?>
            </select>
            <button type="button" class="svz-cal__today" data-svz-cal-today>Heute</button>
        </div>
    </div>

    <div class="svz-cal__months">
        <?php foreach ($months as $index => $month): ?>
            <?php
            /** @var DateTimeImmutable $first */
            $first = $month['first'];
            $year = (int)$first->format('Y');
            $monthNumber = (int)$first->format('n');
            $daysInMonth = (int)$first->format('t');
            $offset = (int)$first->format('N') - 1;
            ?>
            <section class="svz-month<?= $index === $activeIndex ? ' is-active' : '';?>" data-svz-month="<?= $index;?>" aria-label="<?= esc($month['title']);?>">
                <h3 class="svz-month__title"><?= esc($month['title']);?></h3>
                <div class="svz-month__weekdays" aria-hidden="true">
                    <?php foreach ($weekdays as $weekday): ?>
                        <span><?= $weekday;?></span>
                    <?php endforeach; ?>
                </div>
                <ol class="svz-month__days">
                    <?php for ($blank = 0; $blank < $offset; $blank++): ?>
                        <li class="svz-day svz-day--blank" aria-hidden="true"></li>
                    <?php endfor; ?>
                    <?php for ($day = 1; $day <= $daysInMonth; $day++): ?>
                        <?php
                        $date = $first->setDate($year, $monthNumber, $day);
                        $key = $date->format('Y-m-d');
                        $classes = ['svz-day'];

                        if (!$available) {
                            $classes[] = 'is-unknown';
                            $state = '';
                        } elseif (isset($bookings[$key])) {
                            $classes[] = 'is-booked';
                            $state = 'belegt';
                        } else {
                            $classes[] = 'is-free';
                            $state = 'frei';
                        }

                        if ($date < $today) {
                            $classes[] = 'is-past';
                        }

                        if ($date == $today) {
                            $classes[] = 'is-today';
                        }

                        if (in_array($date->format('N'), ['6', '7'], true)) {
                            $classes[] = 'is-weekend';
                        }

                        $label = $weekdaysLong[$weekdays[(int)$date->format('N') - 1]] . ', ' . $day . '. ' . $monthNames[$monthNumber - 1] . ' ' . $year;
                        ?>
                        <li class="<?= implode(' ', $classes);?>" data-date="<?= $key;?>" title="<?= esc($label . ($state !== '' ? ': ' . $state : ''));?>">
                            <span class="svz-day__num"><?= $day;?></span>
                            <?php if ($state !== ''): ?>
                                <span class="svz-day__state"><?= $state;?></span>
                            <?php endif; ?>
                        </li>
                    <?php endfor; ?>
                </ol>
            </section>
        <?php endforeach; ?>
    </div>

    <?php if ($freeSaturdays !== []): ?>
        <div class="svz-cal__free">
            <h4 class="svz-cal__free-title"><i class="fa fa-star" aria-hidden="true"></i> Die nächsten freien Samstage</h4>
            <ul class="svz-chips">
                <?php foreach ($freeSaturdays as $saturday): ?>
                    <li>
                        <?php $monthIndex = ((int)$saturday->format('Y') - (int)$firstMonth->format('Y')) * 12 + (int)$saturday->format('n') - (int)$firstMonth->format('n');?>
                        <a href="#verfuegbarkeit" data-svz-goto-month="<?= $monthIndex;?>">
                            <?= $saturday->format('d.m.Y');?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

</div>
