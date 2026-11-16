<?php
/**
 * Sketch of the floor plan of the SVZ as inline SVG (data and coordinates: see SvzFloorplan).
 *
 * The walls are the gaps between the rooms: the outline of the building is filled with the wall color and the
 * rooms are drawn on top of it, so the wall thicknesses of the original plan are kept.
 * The text of a room (name, dimension, area) is centered in the first rect of the room.
 * Garderobe and dining room are an open area: there is no wall between them, only the colors change.
 *
 * @var array $rooms rooms of the sketch, see SvzFloorplan::rooms()
 */

// pixels per meter, the viewBox scales the sketch to the width of the page
$scale = 40;
$margin = ['top' => 30, 'right' => 24, 'bottom' => 58, 'left' => 60];

$viewWidth = round(SvzFloorplan::WIDTH * $scale + $margin['left'] + $margin['right'], 1);
$viewHeight = round(SvzFloorplan::DEPTH * $scale + $margin['top'] + $margin['bottom'], 1);

// meters (origin: south-west corner, y points upwards) to SVG coordinates (y points downwards)
$x = fn(float $meters): float => round($margin['left'] + $meters * $scale, 1);
$y = fn(float $meters): float => round($margin['top'] + (SvzFloorplan::DEPTH - $meters) * $scale, 1);
$length = fn(float $meters): float => round($meters * $scale, 1);

// rect [x, y, width, height] in meters as attributes of a <rect>
$rectAttributes = fn(array $rect): string => sprintf(
    'x="%s" y="%s" width="%s" height="%s"',
    $x($rect[0]),
    $y($rect[1] + $rect[3]),
    $length($rect[2]),
    $length($rect[3])
);

// swing of a door as path: the leaf in open position and the arc to the closed position
$swingPath = function (array $swing) use ($x, $y): string {
    [$hingeX, $hingeY] = [$x($swing['hinge'][0]), $y($swing['hinge'][1])];
    [$openX, $openY] = [$x($swing['open'][0]), $y($swing['open'][1])];
    [$closedX, $closedY] = [$x($swing['closed'][0]), $y($swing['closed'][1])];
    $radius = round(hypot($openX - $hingeX, $openY - $hingeY), 1);
    $clockwise = (($openX - $hingeX) * ($closedY - $hingeY) - ($openY - $hingeY) * ($closedX - $hingeX)) > 0 ? 1 : 0;

    return sprintf('M%s,%s L%s,%s A%s,%s 0 0 %d %s,%s', $hingeX, $hingeY, $openX, $openY, $radius, $radius, $clockwise, $closedX, $closedY);
};

$movableWall = SvzFloorplan::movableWall();
$movableWallCenter = $x($movableWall['rect'][0] + $movableWall['rect'][2] / 2);

// counter: the L-shaped bar as path (rounded outer corner in the south-west) and the bar stools
$counter = SvzFloorplan::counter();
$stools = SvzFloorplan::stools();
$bar = $counter['bar'];
[$barLeft, $barRight] = [$x($bar['x']), $x($bar['x'] + $bar['width'])];
[$barTop, $barBottom] = [$y($bar['y'] + $bar['height']), $y($bar['y'])];
[$barThickness, $barRadius] = [$length($bar['thickness']), $length($bar['radius'])];
$barPath = sprintf(
    'M%s,%s H%s V%s H%s V%s H%s A%s,%s 0 0 1 %s,%s Z',
    $barLeft,
    $barTop,
    $barLeft + $barThickness,
    $barBottom - $barThickness,
    $barRight,
    $barBottom,
    $barLeft + $barRadius,
    $barRadius,
    $barRadius,
    $barLeft,
    $barBottom - $barRadius
);
$topPoints = implode(' ', array_map(fn(array $point): string => $x($point[0]) . ',' . $y($point[1]), $counter['top']));
$counterLabelX = $x($counter['label'][0]);
$counterLabelY = $y($counter['label'][1]);

$outline = implode(' ', array_map(fn(array $point): string => $x($point[0]) . ',' . $y($point[1]), SvzFloorplan::outline()));
$entrance = SvzFloorplan::entrance();
$entranceCenter = $x($entrance[0] + $entrance[2] / 2);

// dimension lines around the building
$lineBottom = $y(0) + 28;
$lineLeft = $x(0) - 28;
?>
<svg class="svz-plan__svg" viewBox="0 0 <?= $viewWidth;?> <?= $viewHeight;?>" role="img" aria-labelledby="svz-plan-title svz-plan-desc" focusable="false">
    <title id="svz-plan-title">Raumskizze des Sportvereinszentrums Bronnen</title>
    <desc id="svz-plan-desc">Vereinfachter Grundriss mit Gastraum, Gymnastik, Geräteraum, Küche, Kühlzelle, Lager, Garderobe, Geschäftszimmer, Eingang und WCs. Garderobe und Gastraum gehen offen ineinander über, Türen verbinden Gastraum und Küche sowie Küche und Lager. Die Wand zwischen Gymnastik und Gastraum ist gestrichelt, sie lässt sich einfahren. Im Gastraum steht die Theke mit <?= count($stools);?> Barhockern. Zu jedem Raum sind Breite mal Tiefe in Metern und die Fläche in Quadratmetern angegeben.</desc>
    <defs>
        <pattern id="svz-plan-hatch" width="9" height="9" patternUnits="userSpaceOnUse" patternTransform="rotate(45)">
            <rect width="9" height="9" class="svz-plan__hatch-background"></rect>
            <line x1="0" y1="0" x2="0" y2="9" class="svz-plan__hatch-line"></line>
        </pattern>
    </defs>

    <polygon class="svz-plan__wall" points="<?= $outline;?>"></polygon>

    <?php foreach (SvzFloorplan::otherRooms() as $rect): ?>
        <rect class="svz-plan__other" <?= $rectAttributes($rect);?> fill="url(#svz-plan-hatch)"></rect>
        <text class="svz-plan__text svz-plan__text--other" x="<?= $x($rect[0] + $rect[2] / 2);?>" y="<?= $y($rect[1] + $rect[3] / 2);?>">weitere Räume</text>
    <?php endforeach; ?>

    <?php foreach ($rooms as $room): ?>
        <?php
        $dimensions = SvzFloorplan::dimensions($room);
        $area = SvzFloorplan::area($room);

        // lines of the label: [text, css class, line height]
        $lines = [];
        foreach ($room['lines'] ?? [$room['name']] as $line) {
            $lines[] = [$line, 'name', 17];
        }
        foreach ($dimensions as $dimension) {
            $lines[] = [$dimension, 'dimension', 15];
        }
        $lines[] = [$area . ' m²', 'area', 15];

        $first = $room['rects'][0];
        $centerX = $x($first[0] + $first[2] / 2);
        $cursor = $y($first[1] + $first[3] / 2) - array_sum(array_column($lines, 2)) / 2;
        // narrow rooms get a smaller font
        $small = $first[2] < 2.6;
        ?>
        <g class="svz-plan__room svz-plan__room--<?= $room['group'];?><?= $small ? ' is-small' : '';?>">
            <title><?= esc($room['name'] . ': ' . implode(' m ', $dimensions) . ' m, ' . $area . ' m²');?></title>
            <?php foreach (array_merge($room['rects'], $room['joints'] ?? []) as $rect): ?>
                <rect class="svz-plan__area" <?= $rectAttributes($rect);?>></rect>
            <?php endforeach; ?>
            <?php foreach ($lines as [$text, $class, $lineHeight]): ?>
                <text class="svz-plan__text svz-plan__text--<?= $class;?>" x="<?= $centerX;?>" y="<?= round($cursor + $lineHeight / 2, 1);?>"><?= esc($text);?></text>
                <?php $cursor += $lineHeight; ?>
            <?php endforeach; ?>
        </g>
    <?php endforeach; ?>

    <g class="svz-plan__counter">
        <title><?= esc($counter['name'] . ' mit ' . count($stools) . ' Barhockern');?></title>
        <polygon class="svz-plan__counter-shape" points="<?= $topPoints;?>"></polygon>
        <path class="svz-plan__counter-shape" d="<?= $barPath;?>"></path>
        <?php foreach ($counter['rects'] as $rect): ?>
            <rect class="svz-plan__counter-shape" <?= $rectAttributes($rect);?>></rect>
        <?php endforeach; ?>
        <text class="svz-plan__counter-label" x="<?= $counterLabelX;?>" y="<?= $counterLabelY;?>" transform="rotate(-90 <?= $counterLabelX;?> <?= $counterLabelY;?>)"><?= esc($counter['name']);?></text>
        <?php foreach ($stools as [$stoolX, $stoolY]): ?>
            <circle class="svz-plan__stool" cx="<?= $x($stoolX);?>" cy="<?= $y($stoolY);?>" r="<?= $length(SvzFloorplan::STOOL_RADIUS);?>"></circle>
        <?php endforeach; ?>
    </g>

    <g class="svz-plan__movable">
        <title><?= esc($movableWall['name']);?></title>
        <rect class="svz-plan__movable-area" <?= $rectAttributes($movableWall['rect']);?>></rect>
        <line class="svz-plan__movable-line" x1="<?= $movableWallCenter;?>" y1="<?= $y($movableWall['rect'][1]);?>" x2="<?= $movableWallCenter;?>" y2="<?= $y($movableWall['rect'][1] + $movableWall['rect'][3]);?>"></line>
    </g>

    <?php foreach (SvzFloorplan::doors() as $door): ?>
        <g class="svz-plan__door">
            <title><?= esc('Tür: ' . $door['name']);?></title>
            <rect class="svz-plan__door-opening" <?= $rectAttributes($door['opening']);?>></rect>
            <?php if (isset($door['swing'])): ?>
                <path class="svz-plan__door-swing" d="<?= $swingPath($door['swing']);?>"></path>
            <?php endif; ?>
        </g>
    <?php endforeach; ?>

    <rect class="svz-plan__entrance" <?= $rectAttributes($entrance);?>></rect>
    <polygon class="svz-plan__arrow" points="<?= ($entranceCenter - 10) . ',' . $y(18.35) . ' ' . ($entranceCenter + 10) . ',' . $y(18.35) . ' ' . $entranceCenter . ',' . $y(17.84);?>"></polygon>

    <g class="svz-plan__measure">
        <line x1="<?= $x(0);?>" y1="<?= $lineBottom;?>" x2="<?= $x(SvzFloorplan::WIDTH);?>" y2="<?= $lineBottom;?>"></line>
        <line x1="<?= $x(0);?>" y1="<?= $lineBottom - 6;?>" x2="<?= $x(0);?>" y2="<?= $lineBottom + 6;?>"></line>
        <line x1="<?= $x(SvzFloorplan::WIDTH);?>" y1="<?= $lineBottom - 6;?>" x2="<?= $x(SvzFloorplan::WIDTH);?>" y2="<?= $lineBottom + 6;?>"></line>
        <text x="<?= $x(SvzFloorplan::WIDTH / 2);?>" y="<?= $lineBottom + 21;?>"><?= SvzFloorplan::meters(SvzFloorplan::WIDTH);?> m</text>

        <line x1="<?= $lineLeft;?>" y1="<?= $y(SvzFloorplan::DEPTH);?>" x2="<?= $lineLeft;?>" y2="<?= $y(0);?>"></line>
        <line x1="<?= $lineLeft - 6;?>" y1="<?= $y(SvzFloorplan::DEPTH);?>" x2="<?= $lineLeft + 6;?>" y2="<?= $y(SvzFloorplan::DEPTH);?>"></line>
        <line x1="<?= $lineLeft - 6;?>" y1="<?= $y(0);?>" x2="<?= $lineLeft + 6;?>" y2="<?= $y(0);?>"></line>
        <text x="<?= $lineLeft - 10;?>" y="<?= $y(SvzFloorplan::DEPTH / 2);?>" transform="rotate(-90 <?= $lineLeft - 10;?> <?= $y(SvzFloorplan::DEPTH / 2);?>)"><?= SvzFloorplan::meters(SvzFloorplan::DEPTH);?> m</text>
    </g>
</svg>
