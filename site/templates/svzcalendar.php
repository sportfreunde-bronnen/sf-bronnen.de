<?php snippet('header'); ?>
<?php snippet('h1'); ?>
<div class="page">
    <div class="main-container container pl-sm-0 pr-sm-0">
        <h2 class="main-heading-1 text-spl-color text-weight-normal text-center text-lg-left"><?= $page->headline();?></h2>
        <div class="row">
        <div class="col-12">
            <?= $page->text()->kirbytext();?>
        </div>
        <div class="col-12">
            <?= $page->facts()->kirbytext();?>
        </div>
        <div class="col-12">
          <hr>
        </div>
        <div class="col-12 p-3">
          <form class="form-inline">
            <select class="form-control" name="calMonth">
              <option value="1"<?= 1 == $month ? ' selected' : '';?>>Januar</option>
              <option value="2"<?= 2 == $month ? ' selected' : '';?>>Februar</option>
              <option value="3"<?= 3 == $month ? ' selected' : '';?>>März</option>
              <option value="4"<?= 4 == $month ? ' selected' : '';?>>April</option>
              <option value="5"<?= 5 == $month ? ' selected' : '';;?>>Mai</option>
              <option value="6"<?= 6 == $month ? ' selected' : '';?>>Juni</option>
              <option value="7"<?= 7 == $month ? ' selected' : '';?>>Juli</option>
              <option value="8"<?= 8 == $month ? ' selected' : '';?>>August</option>
              <option value="9"<?= 9 == $month ? ' selected' : '';?>>September</option>
              <option value="10"<?= 10 == $month ? ' selected' : '';?>>Oktober</option>
              <option value="11"<?= 11 == $month ? ' selected' : '';?>>November</option>
              <option value="12"<?= 12 == $month ? ' selected' : '';?>>Dezember</option>
            </select>
            <select class="form-control ml-sm-2 mt-1 mt-sm-0" name="calYear">
              <?php for($i = date('Y'); $i <= date('Y') + 3; $i++):?>
                <option<?= $year == $i ? ' selected' : '';?>><?= $i;?></option>
              <?php endfor; ?>
            </select>
            <input class="ml-sm-2 mt-3 mt-sm-0 form-control btn btn-primary" type="submit" value="Verfügbarkeit anzeigen"/>
          </form>
        </div>
          <div class="col-12">
            <hr>
            <h3><?= $month; ?> / <?= $year; ?></h3>
          </div>



          <?php

          function build_calendar ($month,$year,$dateArray) {

            $daysOfWeek = array('Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So');

            $firstDayOfMonth = mktime(0,0,0,$month,1,$year);

            $numberDays = date('t',$firstDayOfMonth);

            $dateComponents = getdate($firstDayOfMonth);

            $monthName = $dateComponents['month'];

            $dayOfWeek = $dateComponents['wday']-1;

            if (date('w', $firstDayOfMonth) == 0) {
              $dayOfWeek = 6;
            }

            $calendar = "<table class='table table-bordered calendar w-100'>";
            $calendar .= "<caption>$monthName $year</caption>";
            $calendar .= "<tr>";

            foreach($daysOfWeek as $day) {
              $calendar .= "<th class='header text-center bg-primary text-white'>$day</th>";
            }

            $currentDay = 1;

            $calendar .= "</tr><tr style='height:150px;'>";

            if ($dayOfWeek > 0) {
              $calendar .= "<td class='text-center' colspan='$dayOfWeek'>&nbsp;</td>";
            }

            $month = str_pad($month, 2, "0", STR_PAD_LEFT);

            while ($currentDay <= $numberDays) {
              if ($dayOfWeek == 7) {
                $dayOfWeek = 0;
                $calendar .= "</tr><tr style='height:150px;'>";
              }
              $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
              $date = "$year-$month-$currentDayRel";
              $occupied = array_key_exists($date, $dateArray);
              $class = $occupied ? 'bg-danger' : 'bg-success';
              $text = $occupied ? 'B' : ' F ';
              $calendar .= "<td class='w-auto day text-center $class' rel='$date'><b>$currentDay</b></td>";
              $currentDay++;
              $dayOfWeek++;
            }

            if ($dayOfWeek != 7) {
              $remainingDays = 7 - $dayOfWeek;
              $calendar .= "<td colspan='$remainingDays'>&nbsp;</td>";
            }

            $calendar .= "</tr>";
            $calendar .= "</table>";

            return $calendar;
          }

          $dateComponents = getdate();

          echo build_calendar($month, $year, $bookings);

          ?>

        </div>
        <hr/>
    </div>
</div>
<?php snippet('footer'); ?><?php snippet('close'); ?>
