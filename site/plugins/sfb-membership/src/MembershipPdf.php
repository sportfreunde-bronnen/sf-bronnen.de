<?php

use Kirby\Toolkit\Str;
use setasign\Fpdi\Tcpdf\Fpdi;

/**
 * Fills in the PDF form "Beitrittserklärung und Vollmacht" (data/beitrittserklarung_stand_03.2026.pdf)
 * with the data of a membership application.
 *
 * The PDF has no form fields. Therefore the texts, check marks and signatures are drawn at fixed
 * positions on top of the original pages. All coordinates are given in points as in the PDF itself:
 * the origin is the lower left corner of the page, y grows upwards. A "baseline" is the y coordinate
 * of the dotted line a text is written on.
 *
 * If the PDF form is replaced by a new version, the coordinates below have to be adapted.
 *
 * Page 1: information about the fees (unchanged)
 * Page 2: personal data, group, fee class, family members, SEPA direct debit mandate
 * Page 3: declaration of consent (GDPR)
 * Page 4: appendix with the additional family members (only added if there are family members)
 *
 * Expected data (all raw strings, not HTML-escaped):
 * nachname, vorname, strasse, plz, ort, eintrittsdatum (YYYY-MM-DD), geburtsdatum (YYYY-MM-DD), email, telefon,
 * gruppen (list of group numbers or 'passiv'), beitragsklasse, familie (list of members),
 * iban, bic, kreditinstitut, kontoinhaberNachname, kontoinhaberVorname, datum (DD.MM.YYYY, date of the signatures)
 */
class MembershipPdf
{
    /**
     * Name of the signature font (see fonts/ of the plugin)
     */
    private const SIGNATURE_FONT = 'herrvonmuellerhoff';

    /**
     * Color of the entered texts and of the signatures (dark blue like a ballpoint pen)
     */
    private const INK = [12, 28, 110];

    /**
     * Page 2: positions of the check boxes. The x coordinate of the center is the same for all boxes, the value
     * of the lists below is the baseline of the line of the box. The center of the box is a bit above it.
     */
    private const BOX_CENTER_X = 185.2;

    private const BOX_CENTER_OFFSET = 2.0;

    private const GROUP_BOXES = [
        'passiv' => 568.26,
        '8001' => 548.34,
        '8002' => 537.34,
        '6001' => 519.99,
        '6002' => 508.99,
        '6003' => 497.99,
        '6004' => 486.99,
        '9001' => 472.98,
        '9002' => 461.98,
        '7002' => 442.98,
        '7005' => 431.98,
        '7006' => 420.98,
        '7007' => 409.98,
        '7008' => 398.98,
        '7010' => 387.98,
        '7013' => 376.98,
        'sonstiges' => 365.98,
    ];

    private const FEE_BOXES = [
        'jugend' => 335.48,
        'erwachsene' => 322.35,
        'familie' => 309.21,
    ];

    private Fpdi $pdf;

    private float $pageHeight = 842.0;

    private function __construct()
    {
        $this->pdf = new Fpdi('P', 'pt', 'A4', true, 'UTF-8', false);
    }

    /**
     * Fill in the PDF form
     *
     * @param array $data The data of the application (see above)
     * @param string $template Path of the PDF form
     * @return string The binary content of the filled in PDF
     */
    public static function render(array $data, string $template): string
    {
        $filler = new self();

        return $filler->build($data, $template);
    }

    private function build(array $data, string $template): string
    {
        $pdf = $this->pdf;
        $pdf->setCreator('sf-bronnen.de');
        $pdf->setAuthor($data['vorname'] . ' ' . $data['nachname']);
        $pdf->setTitle('Beitrittserklärung ' . $data['vorname'] . ' ' . $data['nachname']);
        $pdf->setSubject('Beitrittserklärung und Vollmacht');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->setMargins(0, 0, 0);
        $pdf->setAutoPageBreak(false, 0);
        $pdf->setCellPaddings(0, 0, 0, 0);
        $pdf->setFontSubsetting(true);
        $pdf->AddFont(self::SIGNATURE_FONT, '', __DIR__ . '/../fonts/' . self::SIGNATURE_FONT . '.php');

        $pages = $pdf->setSourceFile($template);

        for ($number = 1; $number <= $pages; $number++) {
            $size = $pdf->getTemplateSize($pdf->importPage($number));
            $this->pageHeight = (float) $size['height'];
            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($pdf->importPage($number));

            if ($number === 2) {
                $this->fillPersonalPage($data);
            } elseif ($number === 3) {
                $this->fillConsentPage($data);
            }
        }

        if (!empty($data['familie'])) {
            $this->addFamilyAppendix($data);
        }

        return $pdf->Output('beitrittserklaerung.pdf', 'S');
    }

    /**
     * Page 2: personal data, group, fee class, family members and SEPA direct debit mandate
     */
    private function fillPersonalPage(array $data): void
    {
        // personal data
        $this->text(177.1, 689.99, $data['nachname'] . ', ' . $data['vorname'], 245);
        $this->text(177.1, 671.99, $data['strasse'], 245);
        $this->text(177.1, 653.99, $data['plz'], 42);
        $this->text(247.9, 653.99, $data['ort'], 225);
        $this->text(177.1, 635.99, MembershipApplication::formatDate($data['eintrittsdatum']), 125);
        $this->text(395.0, 635.99, MembershipApplication::formatDate($data['geburtsdatum']), 80);
        $this->text(177.1, 617.99, $data['email'], 128);
        $this->text(377.0, 617.99, $data['telefon'], 98);

        // group / passive membership
        foreach ($data['gruppen'] as $group) {
            if (isset(self::GROUP_BOXES[$group])) {
                $this->check(self::BOX_CENTER_X, self::GROUP_BOXES[$group] + self::BOX_CENTER_OFFSET);
            }
        }

        // fee class
        if (isset(self::FEE_BOXES[$data['beitragsklasse']])) {
            $this->check(self::BOX_CENTER_X, self::FEE_BOXES[$data['beitragsklasse']] + self::BOX_CENTER_OFFSET);
        }

        // additional family members: as many as fit in the line, all of them are listed on the appendix page
        if (!empty($data['familie'])) {
            $this->text(395.0, 309.21, '(siehe Anlage, Seite 4)', 130, 7);

            $entries = array_map(
                fn(array $member) => $member['vorname'] . ' ' . $member['nachname'] . ', ' . MembershipApplication::formatDate($member['geburtsdatum']),
                $data['familie']
            );
            $this->text(272.0, 298.35, $this->fitList($entries, 252, 7), 252, 7);
        }

        // SEPA direct debit mandate
        $this->text(212.5, 201.24, MembershipApplication::formatIban($data['iban']), 205);
        $this->text(430.0, 201.24, MembershipApplication::normalizeBic($data['bic']), 92);
        $this->text(212.5, 172.24, $data['kreditinstitut'], 305);
        $this->text(212.5, 143.24, $data['kontoinhaberNachname'], 112);
        $this->text(354.1, 143.24, $data['kontoinhaberVorname'], 165);

        // date, place and signature of the account holder
        $this->text(141.7, 78.24, $data['datum'] . ', ' . $data['ort'], 180);
        $this->signature($data['kontoinhaberVorname'] . ' ' . $data['kontoinhaberNachname'], 356.0, 82.0, 160);
    }

    /**
     * Page 3: declaration of consent
     */
    private function fillConsentPage(array $data): void
    {
        $this->check(78.1, 399.1, 11.0);

        $this->text(70.9, 255.24, $data['datum'] . ', ' . $data['ort'], 170);
        $this->signature($data['vorname'] . ' ' . $data['nachname'], 252.0, 259.0, 230);
    }

    /**
     * Page 4: appendix with the additional family members
     */
    private function addFamilyAppendix(array $data): void
    {
        $pdf = $this->pdf;
        $pdf->AddPage('P', [595, 842]);
        $this->pageHeight = 842.0;

        $pdf->setTextColor(0, 0, 0);
        $pdf->setFont('helvetica', 'I', 14);
        $pdf->setXY(203.7, 842 - 793.42 - 14);
        $pdf->Cell(0, 0, 'BITTE BEIDSEITIG AUSDRUCKEN', 0, 0, 'L');

        $pdf->setFont('helvetica', 'B', 14);
        $pdf->setXY(70.9, 842 - 772.72 - 14);
        $pdf->Cell(0, 0, 'Anlage zur Beitrittserklärung: weitere Familienmitglieder', 0, 0, 'L');

        $pdf->setFont('helvetica', '', 10);
        $pdf->setXY(70.9, 842 - 745);
        $pdf->MultiCell(453, 0, 'Familienbeitrag für ' . $data['vorname'] . ' ' . $data['nachname'] . ', ' . $data['strasse'] . ', ' . $data['plz'] . ' ' . $data['ort']
            . '. Die folgenden Personen gehören zur Familie und möchten Mitglied werden:', 0, 'L');

        $columns = [
            ['title' => 'Nr.', 'x' => 70.9, 'width' => 24],
            ['title' => 'Name', 'x' => 94.9, 'width' => 150],
            ['title' => 'Geburtsdatum', 'x' => 244.9, 'width' => 78],
            ['title' => 'Gruppe / Abteilung', 'x' => 322.9, 'width' => 200],
        ];
        $top = 842 - 705;
        $rowHeight = 26;

        $pdf->setDrawColor(0, 72, 105);
        $pdf->setFillColor(232, 243, 249);
        $pdf->setLineWidth(0.5);
        $pdf->Rect(70.9, $top, 452.0, 20, 'DF');
        $pdf->setFont('helvetica', 'B', 9);
        foreach ($columns as $column) {
            $pdf->setXY($column['x'] + 4, $top + 6);
            $pdf->Cell($column['width'] - 8, 0, $column['title'], 0, 0, 'L');
        }

        $top += 20;
        foreach ($data['familie'] as $index => $member) {
            $pdf->Rect(70.9, $top, 452.0, $rowHeight, 'D');
            $texts = [
                (string) ($index + 1),
                $member['nachname'] . ', ' . $member['vorname'],
                MembershipApplication::formatDate($member['geburtsdatum']),
                MembershipApplication::groupTitle($member['gruppe']),
            ];

            foreach ($columns as $position => $column) {
                $text = $texts[$position];
                $font = $this->fontFor($text);
                $size = 9.0;
                while ($size > 6 && $pdf->GetStringWidth($text, $font, '', $size) > $column['width'] - 8) {
                    $size -= 0.25;
                }
                $pdf->setFont($font, '', $size);
                $pdf->setTextColor(...self::INK);
                $pdf->setXY($column['x'] + 4, $top + ($rowHeight - $size) / 2 - 1);
                $pdf->Cell($column['width'] - 8, 0, $text, 0, 0, 'L');
            }

            $top += $rowHeight;
        }

        $pdf->setTextColor(0, 0, 0);
        $pdf->setFont('helvetica', 'I', 7);
        $pdf->setXY(247.5, 842 - 35.9 - 7);
        $pdf->Cell(0, 0, 'Beitrittserklärung Stand 03.2026 - Anlage', 0, 0, 'L');
    }

    /**
     * Write a text on a line
     *
     * @param float $x Left edge of the text
     * @param float $baseline y coordinate (PDF) of the line the text is written on
     * @param string $text The text, it is shrunk (or shortened as last resort) to fit into $maxWidth
     * @param float $maxWidth Available width
     * @param float $size Preferred font size, 10 for the fields of the form
     */
    private function text(float $x, float $baseline, string $text, float $maxWidth, float $size = 10.0): void
    {
        if ($text === '') {
            return;
        }

        $pdf = $this->pdf;
        $font = $this->fontFor($text);

        while ($size > 6.0 && $pdf->GetStringWidth($text, $font, '', $size) > $maxWidth) {
            $size -= 0.25;
        }

        while (mb_strlen($text) > 1 && $pdf->GetStringWidth($text, $font, '', $size) > $maxWidth) {
            $text = mb_substr($text, 0, -1);
        }

        // slightly above the dots, so that they stay visible below the text
        $baseline += 2.5;

        $pdf->setFont($font, '', $size);
        $pdf->setTextColor(...self::INK);
        $pdf->setXY($x, $this->pageHeight - $baseline - $pdf->getFontAscent($font, '', $size));
        $pdf->Cell(0, 0, $text, 0, 0, 'L');
    }

    /**
     * Join as many entries as fit into the width, if not all fit the list ends with "..."
     *
     * @param string[] $entries
     */
    private function fitList(array $entries, float $maxWidth, float $size): string
    {
        $pdf = $this->pdf;
        $list = '';

        foreach ($entries as $index => $entry) {
            $candidate = $list === '' ? $entry : $list . '; ' . $entry;
            $more = $index < count($entries) - 1 ? ' ...' : '';

            if ($pdf->GetStringWidth($candidate . $more, $this->fontFor($candidate), '', $size) > $maxWidth) {
                return $list === '' ? $entry : $list . ' ...';
            }

            $list = $candidate;
        }

        return $list;
    }

    /**
     * Check mark in a check box
     *
     * @param float $centerX x coordinate of the center of the box
     * @param float $centerY y coordinate (PDF) of the center of the box
     * @param float $size Size of the check mark
     */
    private function check(float $centerX, float $centerY, float $size = 5.8): void
    {
        $pdf = $this->pdf;
        $centerY = $this->pageHeight - $centerY;
        $style = ['width' => max(0.8, $size / 6), 'cap' => 'round', 'join' => 'round', 'color' => self::INK];

        $pdf->Line($centerX - 0.42 * $size, $centerY - 0.00 * $size, $centerX - 0.12 * $size, $centerY + 0.32 * $size, $style);
        $pdf->Line($centerX - 0.12 * $size, $centerY + 0.32 * $size, $centerX + 0.46 * $size, $centerY - 0.42 * $size, $style);
    }

    /**
     * Generated signature: the name in a handwriting font, slightly slanted
     *
     * @param string $name First and last name
     * @param float $x Left edge of the signature
     * @param float $baseline y coordinate (PDF) of the baseline of the signature
     * @param float $maxWidth Available width
     */
    private function signature(string $name, float $x, float $baseline, float $maxWidth): void
    {
        $pdf = $this->pdf;
        $name = $this->signatureText($name);

        if ($name === '') {
            return;
        }

        $size = 30.0;
        while ($size > 12.0 && $pdf->GetStringWidth($name, self::SIGNATURE_FONT, '', $size) > $maxWidth) {
            $size -= 1.0;
        }

        // the same name always results in the same signature
        $angle = 1.0 + (crc32($name) % 30) / 10;
        $top = $this->pageHeight - $baseline;

        $pdf->setFont(self::SIGNATURE_FONT, '', $size);
        $pdf->setTextColor(...self::INK);
        $pdf->setDrawColor(...self::INK);
        $pdf->StartTransform();
        $pdf->Rotate($angle, $x, $top);
        // a thin stroke around the glyphs makes the signature look like written with a pen
        $pdf->setTextRenderingMode(0.3, true, false);
        $pdf->setXY($x, $top - $pdf->getFontAscent(self::SIGNATURE_FONT, '', $size));
        $pdf->Cell(0, 0, $name, 0, 0, 'L');
        $pdf->setTextRenderingMode(0, true, false);
        $pdf->StopTransform();
    }

    /**
     * Characters which the signature font does not know are replaced or skipped
     */
    private function signatureText(string $name): string
    {
        $pdf = $this->pdf;
        $result = '';

        foreach (mb_str_split(trim($name)) as $char) {
            if ($pdf->isCharDefined($char, self::SIGNATURE_FONT)) {
                $result .= $char;
                continue;
            }

            foreach (str_split(Str::ascii($char)) as $replacementChar) {
                if ($replacementChar !== '' && $pdf->isCharDefined($replacementChar, self::SIGNATURE_FONT)) {
                    $result .= $replacementChar;
                }
            }
        }

        return $result;
    }

    /**
     * Helvetica like the original form, DejaVu Sans for texts with characters which Helvetica does not support
     */
    private function fontFor(string $text): string
    {
        $latin1 = mb_convert_encoding($text, 'ISO-8859-1', 'UTF-8');

        return mb_convert_encoding($latin1, 'UTF-8', 'ISO-8859-1') === $text ? 'helvetica' : 'dejavusans';
    }
}
