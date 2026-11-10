<?php
/**
 * Player statistics of the darts department ("Spielbetrieb" > "Spielerstatistik").
 *
 * The data comes from the website of the DVOS (Dartverband Oberschwaben), is prepared and cached for 12 hours
 * by the plugin sfb-dvos. The style is in frontend/src/sass/spielerstatistik.scss.
 *
 * @var SpielerstatistikPage $page
 */

$statistics = $page->statistics();
$competitions = $statistics['competitions'] ?? [];
// the club is located in Germany, independent of the time zone of the server
$updatedAt = isset($statistics['fetchedAt'])
    ? (new DateTimeImmutable('@' . $statistics['fetchedAt']))->setTimezone(new DateTimeZone('Europe/Berlin'))
    : null;

$categoryLabels = ['damen' => 'Damen', 'junioren' => 'Junioren', 'juniorinnen' => 'Juniorinnen'];

$dash = '<span class="stats-empty">–</span>';

// wins / games and the quote of single, double and team game
$resultCell = function (?array $group) use ($dash): string {
    if (!$group || !($group['games'] ?? 0)) {
        return $dash;
    }

    return '<span class="stats-main">' . SpielerstatistikPage::number($group['wins']) . ' / ' . SpielerstatistikPage::number($group['games']) . '</span>'
        . '<span class="stats-sub">' . SpielerstatistikPage::quote($group['quote']) . '</span>';
};

// number of short games / high finishes and the best one
$bestCell = function (?array $group, string $bestLabel) use ($dash): string {
    $count = $group['count'] ?? null;
    $best = $group['best'] ?? null;

    if ($count === null && $best === null) {
        return $dash;
    }

    return '<span class="stats-main">' . SpielerstatistikPage::number($count) . '×</span>'
        . '<span class="stats-sub">' . $bestLabel . ' ' . SpielerstatistikPage::number($best) . '</span>';
};
?>
<?php snippet('header', ['styles' => ['spielerstatistik']]); ?>
<div class="template--spielerstatistik">
    <section class="main-banner text-xs-center text-sm-center text-md-start">
        <div class="container text-lite-color ps-sm-0 pe-sm-0">
            <h1 class="text-weight-medium"><?= $page->title() . APPEND_TITLE;?></h1>
        </div>
    </section>
    <div class="page">
        <div class="main-container container ps-sm-0 pe-sm-0">
            <?php if ($page->headline()->isNotEmpty()): ?>
                <h2 class="main-heading-1 text-spl-color text-weight-normal text-center text-lg-start"><?= $page->headline();?></h2>
            <?php endif; ?>
            <?php if ($page->text()->isNotEmpty()): ?>
                <div class="stats-intro"><?= $page->text()->kirbytext();?></div>
            <?php endif; ?>

            <?php if ($competitions === []): ?>
                <div class="stats-notice" role="status">
                    <strong>Die Statistik kann gerade nicht geladen werden.</strong>
                    Bitte versuche es später noch einmal oder schau direkt beim
                    <a href="<?= esc($page->sourceUrl(), 'attr');?>" target="_blank" rel="noopener">Dartverband Oberschwaben (DVOS)</a> vorbei.
                </div>
            <?php else: ?>
                <?php if (count($competitions) > 1): ?>
                    <ul class="nav nav-pills stats-tabs" role="tablist">
                        <?php $isFirst = true; ?>
                        <?php foreach ($competitions as $key => $competition): ?>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link<?= $isFirst ? ' active' : '';?>" id="stats-tab-<?= $key;?>" type="button" role="tab" data-bs-toggle="pill" data-bs-target="#stats-<?= $key;?>" aria-controls="stats-<?= $key;?>" aria-selected="<?= $isFirst ? 'true' : 'false';?>">
                                    <?= esc($competition['title']);?><span class="stats-tabs__count"><?= count($competition['players']);?></span>
                                </button>
                            </li>
                            <?php $isFirst = false; ?>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <div class="tab-content">
                    <?php $isFirst = true; ?>
                    <?php foreach ($competitions as $key => $competition): ?>
                        <?php
                        $players = $competition['players'];
                        $highlights = $page->highlights($players);
                        $hasTeamgame = array_filter(array_column($players, 'teamgame')) !== [];
                        ?>
                        <section class="tab-pane fade<?= $isFirst ? ' show active' : '';?>" id="stats-<?= $key;?>" role="tabpanel" aria-labelledby="stats-tab-<?= $key;?>" aria-label="<?= esc($competition['title'], 'attr');?>">
                            <?php if ($highlights !== []): ?>
                                <div class="stats-highlights">
                                    <?php foreach ($highlights as $highlight): ?>
                                        <div class="stats-highlight stats-highlight--<?= $highlight['key'];?>">
                                            <span class="stats-highlight__label"><?= esc($highlight['label']);?><?php if ($highlight['note']): ?> <small>(<?= esc($highlight['note']);?>)</small><?php endif; ?></span>
                                            <strong class="stats-highlight__value"><?= esc($highlight['value']);?></strong>
                                            <span class="stats-highlight__names"><?= esc(implode(', ', $highlight['names']));?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <div class="stats-table-wrap">
                                <table class="stats-table">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="stats-player">Spieler</th>
                                            <th scope="col">Punkte</th>
                                            <th scope="col">Spiele</th>
                                            <th scope="col">Siege</th>
                                            <th scope="col">Quote</th>
                                            <th scope="col">Einzel<small>Siege / Spiele</small></th>
                                            <th scope="col">Doppel<small>Siege / Spiele</small></th>
                                            <?php if ($hasTeamgame): ?>
                                                <th scope="col">Teamgame<small>Siege / Spiele</small></th>
                                            <?php endif; ?>
                                            <th scope="col">180er</th>
                                            <th scope="col">High-Finish<small>Anzahl / höchstes</small></th>
                                            <th scope="col">Shortgame<small>Anzahl / bestes</small></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($players as $player): ?>
                                            <?php
                                            $rank = (int)$player['rank'];
                                            $categoryLabel = $categoryLabels[$player['category']] ?? null;
                                            ?>
                                            <tr<?= $player['active'] ? '' : ' class="is-inactive"';?>>
                                                <th scope="row" class="stats-player">
                                                    <div class="stats-player__inner">
                                                        <span class="stats-rank<?= in_array($rank, [1, 2, 3], true) ? ' stats-rank--' . $rank : '';?>"><?= $player['rank'] ?? '';?></span>
                                                        <span class="stats-player__info">
                                                            <span class="stats-player__name"><?= esc($player['name']);?></span>
                                                            <?php if ($categoryLabel): ?>
                                                                <span class="stats-badge stats-badge--<?= $player['category'];?>"><?= $categoryLabel;?></span>
                                                            <?php endif; ?>
                                                            <?php if (!$player['active']): ?>
                                                                <span class="stats-badge stats-badge--inactive">nicht mehr gemeldet</span>
                                                            <?php endif; ?>
                                                            <?php if ($player['squad']): ?>
                                                                <span class="stats-player__squad"><?= esc($player['squad']);?></span>
                                                            <?php endif; ?>
                                                        </span>
                                                    </div>
                                                </th>
                                                <td class="stats-points"><?= SpielerstatistikPage::number($player['points']);?></td>
                                                <td><?= SpielerstatistikPage::number($player['games']);?></td>
                                                <td><?= SpielerstatistikPage::number($player['wins']);?></td>
                                                <td class="stats-quote">
                                                    <?php if ($player['quote'] === null): ?>
                                                        <?= $dash;?>
                                                    <?php else: ?>
                                                        <span class="stats-main"><?= SpielerstatistikPage::quote($player['quote']);?></span>
                                                        <span class="stats-bar" style="--value: <?= (float)$player['quote'];?>"></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= $resultCell($player['single']);?></td>
                                                <td><?= $resultCell($player['double']);?></td>
                                                <?php if ($hasTeamgame): ?>
                                                    <td><?= $resultCell($player['teamgame']);?></td>
                                                <?php endif; ?>
                                                <td><?= $player['count180'] ? SpielerstatistikPage::number($player['count180']) . '×' : $dash;?></td>
                                                <td><?= $bestCell($player['highfinish'], 'höchstes');?></td>
                                                <td><?= $bestCell($player['shortgame'], 'bestes');?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </section>
                        <?php $isFirst = false; ?>
                    <?php endforeach; ?>
                </div>

                <p class="stats-meta">
                    Stand: <?= $updatedAt->format('d.m.Y, H:i');?> Uhr &middot;
                    Quelle: <a href="<?= esc($statistics['sourceUrl'], 'attr');?>" target="_blank" rel="noopener">Dartverband Oberschwaben (DVOS)</a> &middot;
                    Die Werte werden alle 12 Stunden aktualisiert.
                </p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php snippet('footer'); ?><?php snippet('close'); ?>
