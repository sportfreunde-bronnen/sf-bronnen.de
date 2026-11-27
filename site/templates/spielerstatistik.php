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

// teams the list can be filtered by
$squads = [1, 2];

// wins / games and the quote of single, double and team game
$resultCell = function (?array $group) use ($dash): string {
    if (!$group || !($group['games'] ?? 0)) {
        return $dash;
    }

    return '<span class="stats-main">' . SpielerstatistikPage::number($group['wins']) . ' / ' . SpielerstatistikPage::number($group['games']) . '</span>'
        . '<span class="stats-sub">' . SpielerstatistikPage::quote($group['quote']) . '</span>';
};

// number of short games / high finishes
$countCell = function (?int $count) use ($dash): string {
    return $count ? SpielerstatistikPage::number($count) . '×' : $dash;
};

// best short game / highest high finish
$bestCell = function (?int $best) use ($dash): string {
    return $best ? SpielerstatistikPage::number($best) : $dash;
};

// attribute with the value a column is sorted by (no attribute: no value, always sorted last)
$sortValue = function (int|float|null $value): string {
    return $value === null ? '' : ' data-sort="' . (float)$value . '"';
};

// header cell that sorts the table; $type: "desc" (highest first) or "asc" (lowest first)
$sortHeader = function (string $label, string $type, string $small = '') {
    return '<th scope="col" aria-sort="none">'
        . '<button type="button" class="stats-sort" data-sort-type="' . $type . '">' . $label
        . ($small ? '<small>' . $small . '</small>' : '') . '</button></th>';
};

$detailPanel = function (array $details, string $competitionTitle): string {
    $games = $details['games'] ?? [];
    $updatedAt = isset($details['fetchedAt'])
        ? (new DateTimeImmutable('@' . $details['fetchedAt']))->setTimezone(new DateTimeZone('Europe/Berlin'))->format('d.m.Y')
        : null;
    $form = SpielerstatistikPage::recentForm($games);
    $legs = SpielerstatistikPage::legTotals($games);
    $homeAway = SpielerstatistikPage::homeAwayStats($games);
    $summaryLabels = ['total' => 'Gesamt', 'single' => 'Einzel', 'double' => 'Doppel', 'teamgame' => 'Teamgame'];
    $resultLabels = ['total' => 'Gesamt', 'single' => 'Einzel', 'double' => 'Doppel'];
    $scores = ['3:0', '3:1', '3:2', '2:3', '1:3', '0:3'];
    $html = '<div class="stats-detail__panel">'
        . '<div class="stats-detail__heading"><div><span class="stats-detail__eyebrow">Individuelle Statistik</span>'
        . '<h3>' . esc($competitionTitle) . '</h3>'
        . ($updatedAt !== null ? '<span class="stats-detail__date">Stand: ' . $updatedAt . '</span>' : '')
        . '</div>';

    if ($form !== []) {
        $html .= '<div class="stats-detail__form" aria-label="Form der letzten ' . count($form) . ' Spiele">';
        foreach ($form as $result) {
            $label = ['W' => 'Sieg', 'L' => 'Niederlage', 'D' => 'Unentschieden'][$result];
            $html .= '<span class="stats-form stats-form--' . strtolower($result) . '" title="' . $label . '" aria-label="' . $label . '">' . $result . '</span>';
        }
        $html .= '</div>';
    }
    $html .= '</div>';

    if (($details['summary'] ?? []) !== []) {
        $html .= '<div class="stats-detail__summary">';
        foreach ($summaryLabels as $key => $label) {
            $group = $details['summary'][$key] ?? null;
            if ($group === null || (($group['games'] ?? 0) === 0 && $key !== 'total')) {
                continue;
            }
            $html .= '<div class="stats-detail__metric"><span>' . $label . '</span>'
                . '<strong>' . SpielerstatistikPage::number($group['wins'] ?? null) . ' / ' . SpielerstatistikPage::number($group['games'] ?? null) . '</strong>'
                . '<small>Siege / Spiele · ' . SpielerstatistikPage::quote($group['quote'] ?? null) . '</small></div>';
        }
        $html .= '</div>';
    }

    $html .= '<div class="stats-detail__facts">'
        . '<div><span>180er</span><strong>' . SpielerstatistikPage::number($details['count180'] ?? null) . '×</strong></div>'
        . '<div><span>Legs</span><strong>' . SpielerstatistikPage::number($legs['for']) . ' : ' . SpielerstatistikPage::number($legs['against']) . '</strong></div>'
        . '<div><span>Heim</span><strong>' . SpielerstatistikPage::number($homeAway['home']['wins']) . ' / ' . SpielerstatistikPage::number($homeAway['home']['games']) . '</strong></div>'
        . '<div><span>Auswärts</span><strong>' . SpielerstatistikPage::number($homeAway['away']['wins']) . ' / ' . SpielerstatistikPage::number($homeAway['away']['games']) . '</strong></div>'
        . '</div>';

    foreach ([['shortgames', 'Shortgames'], ['highfinishes', 'High-Finishes']] as [$key, $label]) {
        if (($details[$key] ?? []) === []) {
            continue;
        }
        $html .= '<div class="stats-detail__chips"><span class="stats-detail__label">' . $label . '</span>';
        foreach ($details[$key] as $value) {
            $html .= '<span class="stats-detail__chip">' . SpielerstatistikPage::number($value) . '</span>';
        }
        $html .= '</div>';
    }

    if (($details['results'] ?? []) !== []) {
        $html .= '<div class="stats-detail__results"><h4>Ergebnisverteilung</h4><div class="stats-detail__scroll">'
            . '<table><thead><tr><th scope="col">Spielart</th>';
        foreach ($scores as $score) {
            $html .= '<th scope="col">' . $score . '</th>';
        }
        $html .= '</tr></thead><tbody>';
        foreach ($resultLabels as $key => $label) {
            if (!isset($details['results'][$key])) {
                continue;
            }
            $html .= '<tr><th scope="row">' . $label . '</th>';
            foreach ($scores as $score) {
                $result = $details['results'][$key][$score] ?? [];
                $count = (int)($result['count'] ?? 0);
                $quote = (float)($result['quote'] ?? 0);
                $heat = $quote >= 50 ? ' stats-result--high' : ($quote >= 25 ? ' stats-result--medium' : '');
                $html .= '<td class="' . trim('stats-result' . $heat) . '"><strong>' . SpielerstatistikPage::number($count) . '</strong>'
                    . '<small>' . SpielerstatistikPage::quote($quote) . '</small></td>';
            }
            $html .= '</tr>';
        }
        $html .= '</tbody></table></div></div>';
    }

    if ($games !== []) {
        $typeLabels = ['single' => 'Einzel', 'double' => 'Doppel', 'teamgame' => 'Teamgame'];
        $html .= '<div class="stats-detail__games"><h4>Spielergebnisse</h4><ul>';
        foreach ($games as $game) {
            $result = ($game['won'] ?? false) ? 'gewonnen' : 'verloren';
            $side = ($game['home'] ?? false) ? 'Heim' : 'Auswärts';
            $opponent = trim((string)($game['opponentSquad'] ?? ''));
            $html .= '<li class="stats-game stats-game--' . ($game['won'] ? 'win' : 'loss') . '">'
                . '<time datetime="' . esc($game['date'], 'attr') . '">' . esc($game['dateLabel'] ?? $game['date']) . '</time>'
                . '<span class="stats-game__type">' . ($typeLabels[$game['type']] ?? 'Spiel') . ' · ' . $side . '</span>'
                . '<span class="stats-game__opponents">' . ($opponent !== '' ? 'vs. ' . esc($opponent) : '–') . '</span>'
                . '<span class="stats-game__score" aria-label="' . $result . '">' . SpielerstatistikPage::number($game['legs']['for'] ?? null)
                . ' : ' . SpielerstatistikPage::number($game['legs']['against'] ?? null) . '</span>';
            if (($game['partners'] ?? []) !== []) {
                $html .= '<small>mit ' . esc(implode(', ', $game['partners'])) . '</small>';
            }
            $html .= '</li>';
        }
        $html .= '</ul></div>';
    } else {
        $html .= '<p class="stats-detail__empty">Für diesen Wettbewerb liegen noch keine Spielergebnisse vor.</p>';
    }

    return $html . '</div>';
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

                <div class="stats-squad-filter btn-group mb-3" role="group" aria-label="Nach Mannschaft filtern">
                    <button type="button" class="btn btn-outline-primary active" data-squad="">Alle</button>
                    <?php foreach ($squads as $squad): ?>
                        <button type="button" class="btn btn-outline-primary" data-squad="<?= $squad;?>"><?= $squad;?>. Mannschaft</button>
                    <?php endforeach; ?>
                </div>

                <div class="tab-content">
                    <?php $isFirst = true; ?>
                    <?php foreach ($competitions as $key => $competition): ?>
                        <?php
                        $players = $competition['players'];
                        // highlights and ranks are prepared for all players and for each team separately; the filter only switches
                        $highlightViews = ['' => $page->highlights($players)];
                        $squadRanks = [];
                        foreach ($squads as $squad) {
                            $highlightViews[$squad] = $page->highlights(SpielerstatistikPage::playersOfSquad($players, $squad));
                            $squadRanks[$squad] = SpielerstatistikPage::squadRanks($players, $squad);
                        }
                        $hasTeamgame = array_filter(array_column($players, 'teamgame')) !== [];
                        ?>
                        <section class="tab-pane fade<?= $isFirst ? ' show active' : '';?>" id="stats-<?= $key;?>" role="tabpanel" aria-labelledby="stats-tab-<?= $key;?>" aria-label="<?= esc($competition['title'], 'attr');?>">
                            <?php foreach ($highlightViews as $squad => $highlights): ?>
                                <?php if ($highlights !== []): ?>
                                    <div class="stats-highlights" data-squad-view="<?= $squad;?>"<?= $squad === '' ? '' : ' hidden';?>>
                                        <?php foreach ($highlights as $highlight): ?>
                                            <div class="stats-highlight stats-highlight--<?= $highlight['key'];?>">
                                                <span class="stats-highlight__label"><?= esc($highlight['label']);?><?php if ($highlight['note']): ?> <small>(<?= esc($highlight['note']);?>)</small><?php endif; ?></span>
                                                <strong class="stats-highlight__value"><?= esc($highlight['value']);?></strong>
                                                <span class="stats-highlight__names"><?= esc(implode(', ', $highlight['names']));?></span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>

                            <div class="stats-table-wrap">
                                <table class="stats-table">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="stats-player">Spieler</th>
                                            <?= $sortHeader('Punkte', 'desc');?>
                                            <?= $sortHeader('Spiele', 'desc');?>
                                            <?= $sortHeader('Siege', 'desc');?>
                                            <?= $sortHeader('Quote', 'desc');?>
                                            <?= $sortHeader('Einzel', 'desc', 'Siege / Spiele');?>
                                            <?= $sortHeader('Doppel', 'desc', 'Siege / Spiele');?>
                                            <?php if ($hasTeamgame): ?>
                                                <?= $sortHeader('Teamgame', 'desc', 'Siege / Spiele');?>
                                            <?php endif; ?>
                                            <?= $sortHeader('180er', 'desc');?>
                                            <?= $sortHeader('High-Finishes', 'desc');?>
                                            <?= $sortHeader('Bestes HF', 'desc');?>
                                            <?= $sortHeader('Shortgames', 'desc');?>
                                            <?= $sortHeader('Bestes SG', 'asc');?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($players as $player): ?>
                                            <?php
                                            $rank = (int)$player['rank'];
                                            $categoryLabel = $categoryLabels[$player['category']] ?? null;
                                            $playerKey = SpielerstatistikPage::playerKey($player);
                                            $playerDetails = $player['details']['competitions'][$key] ?? null;
                                            if (is_array($playerDetails) && isset($player['details']['fetchedAt'])) {
                                                $playerDetails['fetchedAt'] = $player['details']['fetchedAt'];
                                            }
                                            $detailId = 'stats-detail-' . $key . '-' . ($player['id'] ?? $playerKey);
                                            $hasDetails = is_array($playerDetails);
                                            ?>
                                            <tr class="stats-row<?= $player['active'] ? '' : ' is-inactive';?>" data-squad="<?= SpielerstatistikPage::squadNumber($player['squad']);?>"<?php foreach ($squads as $squad): ?><?= isset($squadRanks[$squad][$playerKey]) ? ' data-rank-' . $squad . '="' . $squadRanks[$squad][$playerKey] . '"' : '';?><?php endforeach; ?>>
                                                <th scope="row" class="stats-player">
                                                    <div class="stats-player__inner">
                                                        <span class="stats-rank<?= in_array($rank, [1, 2, 3], true) ? ' stats-rank--' . $rank : '';?>" data-rank-all="<?= $player['rank'] ?? '';?>"><?= $player['rank'] ?? '';?></span>
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
                                                        <?php if ($hasDetails): ?>
                                                            <button class="stats-toggle" type="button" aria-expanded="false" aria-controls="<?= esc($detailId, 'attr');?>" aria-label="Details zu <?= esc($player['name'], 'attr');?> anzeigen">
                                                                <span class="stats-toggle__icon" aria-hidden="true">+</span>
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>
                                                </th>
                                                <td class="stats-points"<?= $sortValue($player['points']);?>><?= SpielerstatistikPage::number($player['points']);?></td>
                                                <td<?= $sortValue($player['games']);?>><?= SpielerstatistikPage::number($player['games']);?></td>
                                                <td<?= $sortValue($player['wins']);?>><?= SpielerstatistikPage::number($player['wins']);?></td>
                                                <td class="stats-quote"<?= $sortValue($player['quote']);?>>
                                                    <?php if ($player['quote'] === null): ?>
                                                        <?= $dash;?>
                                                    <?php else: ?>
                                                        <span class="stats-main"><?= SpielerstatistikPage::quote($player['quote']);?></span>
                                                        <span class="stats-bar" style="--value: <?= (float)$player['quote'];?>"></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td<?= $sortValue(($player['single']['games'] ?? 0) ? $player['single']['quote'] : null);?>><?= $resultCell($player['single']);?></td>
                                                <td<?= $sortValue(($player['double']['games'] ?? 0) ? $player['double']['quote'] : null);?>><?= $resultCell($player['double']);?></td>
                                                <?php if ($hasTeamgame): ?>
                                                    <td<?= $sortValue(($player['teamgame']['games'] ?? 0) ? $player['teamgame']['quote'] : null);?>><?= $resultCell($player['teamgame']);?></td>
                                                <?php endif; ?>
                                                <td<?= $sortValue($player['count180'] ?: null);?>><?= $countCell($player['count180']);?></td>
                                                <td<?= $sortValue($player['highfinish']['count'] ?? null);?>><?= $countCell($player['highfinish']['count'] ?? null);?></td>
                                                <td<?= $sortValue($player['highfinish']['best'] ?? null);?>><?= $bestCell($player['highfinish']['best'] ?? null);?></td>
                                                <td<?= $sortValue($player['shortgame']['count'] ?? null);?>><?= $countCell($player['shortgame']['count'] ?? null);?></td>
                                                <td<?= $sortValue($player['shortgame']['best'] ?? null);?>><?= $bestCell($player['shortgame']['best'] ?? null);?></td>
                                            </tr>
                                            <?php if ($hasDetails): ?>
                                                <tr class="stats-detail" id="<?= esc($detailId, 'attr');?>" hidden>
                                                    <td colspan="<?= $hasTeamgame ? 13 : 12;?>" class="stats-detail__cell">
                                                        <?= $detailPanel($playerDetails, $competition['title']);?>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
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
<script>
(function () {
    var buttons = document.querySelectorAll('.stats-squad-filter [data-squad]');
    var squad = '';

    // ranks in front of the players: the original ranks (with gold, silver and bronze badges) in the original order,
    // numbered anew by the sorted column (without badges, same value = same rank) as soon as a column is sorted:
    // from 1 upwards when sorted descending, from the number of players downwards when sorted ascending
    function updateRanks() {
        document.querySelectorAll('.stats-table').forEach(function (table) {
            var sorted = table.querySelector('thead th[aria-sort="ascending"], thead th[aria-sort="descending"]');
            var column = sorted ? Array.prototype.indexOf.call(table.tHead.rows[0].cells, sorted) : -1;
            var ascending = !!sorted && sorted.getAttribute('aria-sort') === 'ascending';
            var rows = table.querySelectorAll('tbody tr.stats-row');
            var total = Array.prototype.filter.call(rows, function (row) { return !row.hidden; }).length;
            var position = 0;
            var rank = 0;
            var previous = null;

            Array.prototype.forEach.call(rows, function (row) {
                if (row.hidden) { return; }
                var badge = row.querySelector('.stats-rank');
                var value;

                if (sorted) {
                    var raw = row.cells[column].getAttribute('data-sort');
                    var key = raw === null || raw === '' ? '' : String(parseFloat(raw));
                    position++;
                    // descending: ranks start at 1; ascending: ranks start at the number of players and count down
                    if (position === 1 || key !== previous) { rank = ascending ? total - position + 1 : position; }
                    previous = key;
                    value = String(rank);
                } else {
                    // ranks start at 1 again within a team
                    value = (squad === '' ? badge : row).getAttribute(squad === '' ? 'data-rank-all' : 'data-rank-' + squad) || '';
                }

                badge.textContent = value;
                badge.classList.remove('stats-rank--1', 'stats-rank--2', 'stats-rank--3');
                if (!sorted && ['1', '2', '3'].indexOf(value) !== -1) { badge.classList.add('stats-rank--' + value); }
            });
        });
    }

    buttons.forEach(function (button) {
        button.addEventListener('click', function () {
            squad = button.getAttribute('data-squad');
            buttons.forEach(function (other) { other.classList.toggle('active', other === button); });
            document.querySelectorAll('.stats-table tbody tr.stats-row').forEach(function (row) {
                row.hidden = squad !== '' && row.getAttribute('data-squad') !== squad;
                var detail = row.nextElementSibling;
                if (detail && detail.classList.contains('stats-detail')) {
                    var toggle = row.querySelector('.stats-toggle');
                    detail.hidden = row.hidden || !toggle || toggle.getAttribute('aria-expanded') !== 'true';
                }
            });
            updateRanks();
            document.querySelectorAll('.stats-highlights').forEach(function (highlights) {
                highlights.hidden = highlights.getAttribute('data-squad-view') !== squad;
            });
        });
    });

    document.querySelectorAll('.stats-row').forEach(function (row) {
        var toggle = row.querySelector('.stats-toggle');
        if (!toggle) { return; }
        var detail = row.nextElementSibling;

        function setExpanded(expanded) {
            toggle.setAttribute('aria-expanded', expanded ? 'true' : 'false');
            toggle.setAttribute('aria-label', toggle.getAttribute('aria-label').replace(expanded ? 'anzeigen' : 'schließen', expanded ? 'schließen' : 'anzeigen'));
            toggle.querySelector('.stats-toggle__icon').textContent = expanded ? '−' : '+';
            detail.hidden = !expanded || row.hidden;
        }

        row.addEventListener('click', function (event) {
            if (event.target.closest('a, button, input, select, textarea')) {
                if (event.target.closest('.stats-toggle')) {
                    setExpanded(toggle.getAttribute('aria-expanded') !== 'true');
                }
                return;
            }
            setExpanded(toggle.getAttribute('aria-expanded') !== 'true');
        });
    });

    // sorting by a single column: first click highest first (best shortgame lowest first), second click reverses
    document.querySelectorAll('.stats-table').forEach(function (table) {
        var headers = table.querySelectorAll('thead th');
        var tbody = table.querySelector('tbody');

        headers.forEach(function (header, column) {
            var button = header.querySelector('.stats-sort');
            if (!button) { return; }
            button.addEventListener('click', function () {
                var type = button.getAttribute('data-sort-type');
                var firstDirection = type === 'desc' ? -1 : 1;
                var current = header.getAttribute('aria-sort');
                var direction = current === 'none' ? firstDirection : (current === 'ascending' ? -1 : 1);

                headers.forEach(function (other) { other.setAttribute('aria-sort', 'none'); });
                header.setAttribute('aria-sort', direction === 1 ? 'ascending' : 'descending');

                var rows = Array.prototype.map.call(tbody.querySelectorAll('tr.stats-row'), function (row, index) {
                    var cell = row.cells[column];
                    var raw = cell.getAttribute('data-sort');
                    var detail = row.nextElementSibling;
                    return {
                        row: row,
                        detail: detail && detail.classList.contains('stats-detail') ? detail : null,
                        index: index,
                        raw: raw,
                        empty: raw === null || raw === ''
                    };
                });

                rows.sort(function (a, b) {
                    // players without a value always stay at the end
                    if (a.empty !== b.empty) { return a.empty ? 1 : -1; }
                    var result = 0;
                    if (!a.empty) {
                        result = parseFloat(a.raw) - parseFloat(b.raw);
                    }
                    return result * direction || a.index - b.index;
                });

                rows.forEach(function (item) {
                    tbody.appendChild(item.row);
                    if (item.detail) { tbody.appendChild(item.detail); }
                });
                updateRanks();
            });
        });
    });
})();
</script>
<?php snippet('footer'); ?><?php snippet('close'); ?>
