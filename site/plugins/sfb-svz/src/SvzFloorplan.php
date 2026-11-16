<?php

/**
 * Floor plan sketch of the Sportvereinszentrum (SVZ), see template svzplan and snippet svz/floorplan.
 *
 * The data is taken from the building application "Neubau eines Vereinsheimes" (Grundriss M 1:100, 28.01.2008).
 * Only the rooms which are relevant for guests are part of the sketch. All other rooms of the building
 * (changing rooms, showers, technical room, ...) are shown as grey areas without a name.
 *
 * Coordinates are given in meters. The origin is the outer south-west corner of the building, x points to the
 * right and y points upwards (the sketch has the same orientation as the original plan).
 * A rect is [x, y, width, height] and describes the clear inside of a room, the walls are the gaps between the rects.
 *
 * Dimensions: the width of a rect is its extent from left to right, the depth its extent from bottom to top.
 * The areas are the net floor areas of the original plan (DIN 277). Because of niches and door openings they
 * may differ slightly from width x depth, that is why both values are shown.
 */
class SvzFloorplan
{
    /** Width of the building in meters (outer dimension) */
    public const WIDTH = 27.175;

    /** Depth of the building in meters (outer dimension) */
    public const DEPTH = 18.99;

    /** Radius of a bar stool in meters (diameter 0.4 m) */
    public const STOOL_RADIUS = 0.2;

    /**
     * Groups of rooms (colors of the sketch)
     *
     * @return array<string, string> key => label
     */
    public static function groups(): array
    {
        return [
            'saal' => 'Gast- und Sporträume',
            'kueche' => 'Küche & Lager',
            'empfang' => 'Eingang & Verwaltung',
            'wc' => 'WCs',
        ];
    }

    /**
     * The rooms of the sketch in the order of the room list
     *
     * Optional keys: 'lines' (name split over several lines), 'joints' (rects which are only drawn to connect the
     * parts of an L-shaped room or to bridge a removed wall, they are no part of the dimensions)
     *
     * @return array<string, array{name: string, group: string, rects: array<int, array<int, float>>, area: float, lines?: array<int, string>, joints?: array<int, array<int, float>>}>
     */
    public static function rooms(): array
    {
        return [
            'geraete' => [
                'name' => 'Lager',
                'group' => 'saal',
                'rects' => [[0.30, 0.30, 3.76, 8.70]],
                'area' => 32.99,
            ],
            'gymnastik' => [
                'name' => 'Gymnastik',
                'group' => 'saal',
                'rects' => [[4.235, 0.30, 10.395, 8.70]],
                'area' => 90.44,
            ],
            'gastraum' => [
                'name' => 'Gastraum',
                'group' => 'saal',
                'rects' => [[14.805, 0.30, 8.135, 8.70]],
                // the wall to the garderobe is removed (open area): the lower half of the former wall belongs to the dining room
                // (reaches 0.05 m into the room, so there is no seam at the edge)
                'joints' => [[17.865, 8.95, 5.075, 0.175]],
                'area' => 71.47,
            ],
            'garderobe' => [
                'name' => 'Eingangsbereich',
                'group' => 'empfang',
                'rects' => [[17.865, 9.24, 5.135, 2.385]],
                // the upper half of the former wall to the dining room (overlaps the dining room and reaches 0.075 m into
                // the garderobe, so there is no seam at the edges)
                'joints' => [[17.865, 9.115, 5.075, 0.2]],
                'area' => 12.25,
            ],
            'geschaeftszimmer' => [
                'name' => 'Geschäftszimmer',
                'group' => 'empfang',
                'rects' => [[17.865, 11.74, 3.01, 5.70]],
                'area' => 17.16,
                'lines' => ['Geschäfts-', 'zimmer'],
            ],
            'eingang' => [
                'name' => 'Garderobe',
                'group' => 'empfang',
                'rects' => [[20.99, 11.625, 2.01, 5.80]],
                'area' => 11.66,
            ],
            'wc-beh-personal' => [
                'name' => 'WC P. m. Behinderung',
                'group' => 'wc',
                'rects' => [[23.115, 16.24, 3.76, 2.45]],
                'area' => 9.21,
            ],
            'wc-damen' => [
                'name' => 'WC Damen',
                'group' => 'wc',
                'rects' => [[23.115, 14.175, 3.76, 1.89]],
                'area' => 7.11,
            ],
            'wc-herren' => [
                'name' => 'WC Herren',
                'group' => 'wc',
                'rects' => [[23.115, 11.74, 3.76, 2.26]],
                'area' => 8.50,
            ],
            'lager' => [
                'name' => 'Lager',
                'group' => 'kueche',
                'rects' => [[23.115, 9.24, 3.76, 2.385]],
                'area' => 8.97,
            ],
            'kuehlzelle' => [
                'name' => 'Kühlzelle',
                'group' => 'kueche',
                'rects' => [[23.115, 7.00, 2.16, 2.00]],
                'area' => 4.32,
            ],
            // L-shaped: the kitchen surrounds the cooling room
            'kueche' => [
                'name' => 'Küche',
                'group' => 'kueche',
                'rects' => [[23.115, 0.30, 3.76, 6.585], [25.39, 7.00, 1.485, 2.00]],
                // slightly overlaps both parts, so no seam is visible
                'joints' => [[25.39, 6.87, 1.485, 0.145]],
                'area' => 27.73,
            ],
        ];
    }

    /**
     * Outline of the building (outer faces of the walls)
     *
     * @return array<int, array<int, float>> points [x, y]
     */
    public static function outline(): array
    {
        return [
            [0, 0],
            [self::WIDTH, 0],
            [self::WIDTH, self::DEPTH],
            [23.0, self::DEPTH],
            [23.0, 17.74],
            [9.05, 17.74],
            [9.05, self::DEPTH],
            [0, self::DEPTH],
        ];
    }

    /**
     * Rooms of the building which are not part of the sketch (shown as hatched areas)
     *
     * @return array<int, array<int, float>> rects [x, y, width, height]
     */
    public static function otherRooms(): array
    {
        return [
            [0.30, 9.24, 8.575, 9.45],
            [9.05, 9.24, 8.70, 8.20],
        ];
    }

    /**
     * Door opening of the entrance in the north wall of the building (rect [x, y, width, height])
     *
     * @return array<int, float>
     */
    public static function entrance(): array
    {
        return [21.0, 17.40, 1.4, 0.34];
    }

    /**
     * Doors between the rooms of the sketch (taken from the original plan)
     *
     * There is no door between garderobe and dining room: the wall between them is removed, it is an open area.
     *
     * 'opening' is the gap in the wall between the two rooms (rect [x, y, width, height]).
     * 'swing' is only given for doors with a leaf: the hinge, the end of the leaf when it is open and the end of the
     * leaf when it is closed (all points [x, y]). The door opens towards the room where the open leaf points to.
     *
     * @return array<string, array{name: string, opening: array<int, float>, swing?: array{hinge: array<int, float>, open: array<int, float>, closed: array<int, float>}}>
     */
    public static function doors(): array
    {
        return [
            // opens into the kitchen
            'gastraum-kueche' => [
                'name' => 'Gastraum – Küche',
                'opening' => [22.94, 5.985, 0.175, 0.9],
                'swing' => [
                    'hinge' => [23.115, 6.885],
                    'open' => [24.015, 6.885],
                    'closed' => [23.115, 5.985],
                ],
            ],
            // opens into the storage room
            'kueche-lager' => [
                'name' => 'Küche – Lager',
                'opening' => [25.75, 9.0, 1.0, 0.24],
                'swing' => [
                    'hinge' => [26.75, 9.24],
                    'open' => [26.75, 10.24],
                    'closed' => [25.75, 9.24],
                ],
            ],
        ];
    }

    /**
     * Movable partition wall between the gym and the dining room (drawn dashed, it can be folded away)
     *
     * @return array{name: string, rect: array<int, float>} rect [x, y, width, height]: the wall between the two rooms
     */
    public static function movableWall(): array
    {
        return [
            'name' => 'Einfahrbare Trennwand zwischen Gymnastik und Gastraum',
            'rect' => [14.63, 0.30, 0.175, 8.70],
        ];
    }

    /**
     * Counter (Theke) in the dining room, measured from the original plan
     *
     * 'top' is the counter at the north side as polygon (points [x, y], clockwise): the counter block, the connecting
     * piece along the north side (the wall to the garderobe is removed, so the counter is closed there) and the
     * cabinet at the wall to the kitchen. It reaches up to the former wall, i.e. to the garderobe.
     * 'rects' are further straight parts (cabinet at the wall to the kitchen, rect [x, y, width, height]).
     * 'bar' is the L-shaped bar for the guests: its bounding box (x, y = south-west corner, width, height), the
     * thickness of both legs and the radius of the rounded outer corner in the south-west.
     * 'label' is the center of the caption (rotated by 90 degrees).
     *
     * @return array{name: string, top: array<int, array<int, float>>, rects: array<int, array<int, float>>, bar: array<string, float>, label: array<int, float>}
     */
    public static function counter(): array
    {
        return [
            'name' => 'Theke',
            'top' => [
                // counter block: 1.01 m wide, from the bar up to the garderobe
                [20.35, 6.85],
                [21.36, 6.85],
                // connecting piece along the north side (0.6 m deep)
                [21.36, 8.64],
                [22.36, 8.64],
                // cabinet at the wall to the kitchen (0.58 m wide)
                [22.36, 6.99],
                [22.94, 6.99],
                [22.94, 9.24],
                [20.35, 9.24],
            ],
            'rects' => [
                // cabinet at the wall to the kitchen
                [22.54, 4.44, 0.40, 1.35],
            ],
            'bar' => ['x' => 20.35, 'y' => 3.45, 'width' => 2.59, 'height' => 2.48, 'thickness' => 1.0, 'radius' => 1.0],
            'label' => [20.855, 8.045],
        ];
    }

    /**
     * Bar stools in front of the counter (centers [x, y] in meters, radius see STOOL_RADIUS)
     *
     * The stools of the L-shaped bar stand on a line 1.33 m around its outer corner (angles of 180, 210, 240 and 270 degrees).
     *
     * @return array<int, array<int, float>>
     */
    public static function stools(): array
    {
        return [
            // counter block
            [20.02, 8.47],
            [20.02, 7.84],
            [20.02, 7.20],
            // L-shaped bar: along the straight leg, around the corner and along the front
            [20.02, 5.74],
            [20.02, 5.09],
            [20.02, 4.45],
            [20.20, 3.785],
            [20.685, 3.30],
            [21.35, 3.12],
            [22.0, 3.12],
            [22.65, 3.12],
        ];
    }

    /**
     * Number in the German notation with two decimals, e.g. 10,40
     */
    public static function format(float $value): string
    {
        return number_format($value, 2, ',', '');
    }

    /**
     * Length in meters like in the original plan: two decimals, a third one only if needed (3,76 / 10,395)
     */
    public static function meters(float $value): string
    {
        $formatted = number_format($value, 3, ',', '');

        return str_ends_with($formatted, '0') ? substr($formatted, 0, -1) : $formatted;
    }

    /**
     * Dimension of every part of a room (width x depth) in meters, e.g. ['3,76 × 8,70'].
     * Parts of an L-shaped room after the first one are prefixed with "+ ".
     *
     * @param array{rects: array<int, array<int, float>>} $room
     * @return array<int, string>
     */
    public static function dimensions(array $room): array
    {
        $lines = [];

        foreach ($room['rects'] as $index => $rect) {
            $lines[] = ($index > 0 ? '+ ' : '') . self::meters($rect[2]) . ' × ' . self::meters($rect[3]);
        }

        return $lines;
    }

    /**
     * Area of the room in m², e.g. 90,44
     *
     * @param array{area: float} $room
     */
    public static function area(array $room): string
    {
        return self::format($room['area']);
    }
}
