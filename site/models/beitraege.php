<?php

/**
 * Class BeitraegePage
 *
 * Page "Beiträge" (annual membership fees). Besides the fee cards it offers a fee calculator, see
 * public/assets/js/fee-calculator.js. The calculator reads its values from the structure field "beitraege"
 * of the page, i.e. a change of the fees in the panel is picked up automatically.
 *
 * @author Magnus Buk <MagnusBuk@gmx.de>
 */
class BeitraegePage extends RoleAwarePage
{
    /**
     * Fee classes of the calculator. The rows of the structure field "beitraege" are assigned by a part of their name
     * (case-insensitive), e.g. "Erwachsene ab 18 Jahren" belongs to "erwachsene".
     *
     * 'family' = the base fee is charged once per family, the department fees (per head) for every family member.
     * 'departments' = false: no department can be chosen (passive members only pay the base fee).
     */
    public const FEE_CLASSES = [
        'jugend' => ['label' => 'Jugendlich', 'hint' => 'bis 18 Jahre', 'match' => 'jugend', 'family' => false, 'departments' => true],
        'erwachsene' => ['label' => 'Erwachsen', 'hint' => 'ab 18 Jahren', 'match' => 'erwachsen', 'family' => false, 'departments' => true],
        'familie' => ['label' => 'Familie', 'hint' => 'pro Familie', 'match' => 'famil', 'family' => true, 'departments' => true],
        'passiv' => ['label' => 'Passiv', 'hint' => 'ohne Abteilung', 'match' => 'passiv', 'family' => false, 'departments' => false],
    ];

    /**
     * Departments: key = field of the structure field "beitraege".
     * 'separate' = the fee is not collected together with the membership fee but by a separate direct debit mandate.
     */
    public const DEPARTMENTS = [
        'fussball' => ['label' => 'Fußball', 'separate' => false],
        'gymnastik' => ['label' => 'Gymnastik', 'separate' => false],
        'aikido' => ['label' => 'Aikido', 'separate' => true],
        'darts' => ['label' => 'Darts', 'separate' => false],
    ];

    /**
     * Data of the fee calculator, null if the structure field does not contain a row for every fee class
     * (then the calculator is not shown).
     *
     * A department is available in a fee class if departments can be chosen there at all (not for the passive
     * membership) and its fee is greater than zero.
     *
     * @return array{classes: array<string, array>, departments: array<string, array>, maxMembers: int}|null
     */
    public function calculator(): ?array
    {
        $classes = [];

        foreach (self::FEE_CLASSES as $key => $class) {
            $row = $this->feeRow($class['match']);

            if ($row === null) {
                return null;
            }

            $departments = [];
            foreach (array_keys(self::DEPARTMENTS) as $department) {
                $fee = $row->content()->get($department)->toFloat();

                if ($class['departments'] && $fee > 0) {
                    $departments[$department] = $fee;
                }
            }

            $classes[$key] = [
                'label' => $class['label'],
                'hint' => $class['hint'],
                'family' => $class['family'],
                'base' => $row->content()->get('grundbeitrag')->toFloat(),
                'departments' => $departments,
            ];
        }

        return [
            'classes' => $classes,
            'departments' => self::DEPARTMENTS,
            // the main person plus the additional family members of the membership application
            'maxMembers' => 1 + MembershipApplication::MAX_FAMILY_MEMBERS,
        ];
    }

    /**
     * First row of the structure field "beitraege" whose name contains the given part
     */
    private function feeRow(string $match)
    {
        foreach ($this->beitraege()->toStructure() as $row) {
            if (str_contains(mb_strtolower((string)$row->name()), $match)) {
                return $row;
            }
        }

        return null;
    }
}
