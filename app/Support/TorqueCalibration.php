<?php

namespace App\Support;

/**
 * Torque wrench calibration results and measurement uncertainty according to ISO 6789-2:2017,
 * calculated exactly like the "Data Analysis" sheet of the lab workbook RS-CAL-P11-F02 (checked against
 * "RS-CAL-P11-F02 - 026-786-0001.xlsx"), including its conventions:
 *   - W' = mean relative error + W, keeping the sign of the error (sheet: J135 = G135 + I135)
 *   - b_l = average of both loading positions - average of position 2 (sheet: D123 - E123)
 *   - w_r, w_rep, w_od, w_int and w_l are relative to the clockwise X̄r and used for both directions
 *     (sheet: F142 = F128 ...); the counter-clockwise X̄r is used only when no clockwise readings exist
 *
 * Input (calibration_details of a CalibrationTorque):
 *   targets            [Xa at 20%, 60%, 100%]
 *   readings[cw|ccw]   [point][5 reference readings as taken]
 *   reference[cw|ccw]  a, b, c (correction polynomial of the reference device, in N·m) and u (its relative expanded uncertainty %)
 *   reproducibility    [sequence I..IV][5 readings]          -> brep
 *   geometric          [position 1..4][10 readings]          -> bod
 *   interface          [position 1..4][10 readings]          -> bint
 *   loading_point      [position 1 (-10 mm), 2 (+10 mm)][10] -> bl
 */
class TorqueCalibration
{
    public const DIRECTIONS = ['cw', 'ccw'];

    public const POINTS = 3;

    public const READINGS = 5;

    /** Torque units and their factor to N·m (Help sheet of the workbook) */
    public const UNIT_FACTORS = [
        'N·m' => 1,
        'gf·cm' => 10200,
        'kgf·cm' => 10.2,
        'kgf·m' => 0.102,
        'mN·m' => 1000,
        'cN·m' => 100,
        'N·cm' => 100,
        'ozf·in' => 142,
        'lbf·in' => 8.85,
        'lbf·ft' => 0.738,
    ];

    public static function number($value): ?float
    {
        $value = str_replace(',', '.', trim((string) $value));
        return is_numeric($value) ? (float) $value : null;
    }

    /**
     * @param array $details calibration_details
     * @param string|null $unit torque unit of the readings
     * @param mixed $resolution resolution r of the torque tool
     * @param array $directions directions calibrated (cw / ccw)
     */
    public static function analyse(array $details, ?string $unit, $resolution, array $directions): array
    {
        $factor = self::UNIT_FACTORS[$unit] ?? 1;
        $targets = array_map([self::class, 'number'], array_values((array) ($details['targets'] ?? [])));
        $r = self::number($resolution);

        // components measured once and shared by both directions (as in the workbook)
        $brep = self::spreadOfAverages($details['reproducibility'] ?? []);
        $bod = self::spreadOfAverages($details['geometric'] ?? []);
        $bint = self::spreadOfAverages($details['interface'] ?? []);
        $bl = self::loadingPointVariation($details['loading_point'] ?? []);

        $result = [
            'factor' => $factor,
            'brep' => $brep,
            'bod' => $bod,
            'bint' => $bint,
            'bl' => $bl,
            'directions' => [],
        ];

        // corrected reference values per direction and point
        $corrected = [];
        foreach (self::DIRECTIONS as $direction) {
            $reference = (array) ($details['reference'][$direction] ?? []);
            $coefficients = [
                self::number($reference['a'] ?? null) ?? 1.0,
                self::number($reference['b'] ?? null) ?? 0.0,
                self::number($reference['c'] ?? null) ?? 0.0,
            ];
            foreach (range(0, self::POINTS - 1) as $point) {
                $readings = array_values(array_filter(
                    array_map([self::class, 'number'], array_values((array) ($details['readings'][$direction][$point] ?? []))),
                    fn ($v) => $v !== null
                ));
                $corrected[$direction][$point] = self::correct($readings, $coefficients, $factor);
            }
        }

        foreach (self::DIRECTIONS as $direction) {
            if (!in_array($direction, $directions, true)) {
                continue;
            }
            $uRef = self::number($details['reference'][$direction]['u'] ?? null);
            $rows = [];
            foreach (range(0, self::POINTS - 1) as $point) {
                $values = $corrected[$direction][$point];
                // X̄r of the shared components: clockwise when measured (as the workbook), else this direction
                $sharedValues = in_array('cw', $directions, true) && $corrected['cw'][$point] ? $corrected['cw'][$point] : $values;
                $sharedXr = $sharedValues ? array_sum(array_column($sharedValues, 'corrected')) / count($sharedValues) : null;
                $rows[] = self::point($targets[$point] ?? null, $values, $sharedXr, $r, $uRef, $brep, $bod, $bint, $bl);
            }
            $result['directions'][$direction] = $rows;
        }

        return $result;
    }

    /**
     * Reference readings as taken with their corrected value: (A·x + B·x² + C·x³) in N·m, x converted from the unit.
     */
    private static function correct(array $readings, array $coefficients, float $factor): array
    {
        [$a, $b, $c] = $coefficients;
        return array_map(function ($reading) use ($a, $b, $c, $factor) {
            $x = $reading / $factor;
            return ['taken' => $reading, 'corrected' => ($a * $x + $b * $x ** 2 + $c * $x ** 3) * $factor];
        }, $readings);
    }

    /**
     * One calibration point: errors and the uncertainty budget.
     */
    private static function point(?float $xa, array $values, ?float $sharedXr, ?float $r, ?float $uRef, ?float $brep, ?float $bod, ?float $bint, ?float $bl): array
    {
        $row = ['xa' => $xa, 'xr' => null, 'mean_error' => null, 'w' => null, 'W' => null, 'W_interval' => null, 'readings' => []];
        if ($xa === null || !$values) {
            return $row;
        }

        $corrected = array_column($values, 'corrected');
        $xr = array_sum($corrected) / count($corrected);
        $errors = [];
        foreach ($values as $value) {
            $relativeError = $value['corrected'] != 0 ? ($xa - $value['corrected']) / $value['corrected'] * 100 : null;
            $row['readings'][] = $value + [
                'error' => $xa - $value['corrected'],
                'relative_error' => $relativeError,
                'deviation' => $value['corrected'] - $xr,
            ];
            if ($relativeError !== null) {
                $errors[] = $relativeError;
            }
        }

        $n = count($corrected);
        $bre = self::stdev($corrected);
        // relative standard uncertainties (%) of the uncertainty budget, b * 100 / (X̄r * divisor)
        $relative = fn (?float $b, ?float $mean, float $divisor) => ($b === null || !$mean) ? 0.0 : $b * 100 / ($mean * $divisor);
        $budget = [
            'wmd' => $uRef === null ? 0.0 : $uRef / 2,
            'wr' => $relative($r === null ? null : $r * 0.5, $sharedXr, sqrt(3)),
            'wrep' => $relative($brep === null ? null : $brep * 0.5, $sharedXr, sqrt(3)),
            'wod' => $relative($bod === null ? null : $bod * 0.5, $sharedXr, sqrt(3)),
            'wint' => $relative($bint === null ? null : $bint * 0.5, $sharedXr, sqrt(3)),
            'wl' => $relative($bl === null ? null : $bl * 0.5, $sharedXr, sqrt(3)),
            'wre' => $relative($bre, $xr, sqrt($n)),
        ];
        $w = sqrt(array_sum(array_map(fn ($v) => $v ** 2, $budget)));
        $meanError = $errors ? array_sum($errors) / count($errors) : null;

        return array_merge($row, [
            'xr' => $xr,
            'bre' => $bre,
            'budget' => $budget,
            'mean_error' => $meanError,
            'w' => $w,
            'W' => 2 * $w,
            // as the workbook: W' = a̅s + W with the sign of the mean error
            'W_interval' => $meanError === null ? null : $meanError + 2 * $w,
        ]);
    }

    /**
     * b_l as the workbook: average of the readings of both loading positions minus the average of position 2.
     */
    public static function loadingPointVariation($series): ?float
    {
        $positions = array_map(
            fn ($values) => array_values(array_filter(array_map([self::class, 'number'], array_values((array) $values)), fn ($v) => $v !== null)),
            array_values((array) $series)
        );
        $both = array_merge($positions[0] ?? [], $positions[1] ?? []);
        $second = $positions[1] ?? [];
        if (!($positions[0] ?? []) || !$second) {
            return null;
        }
        return array_sum($both) / count($both) - array_sum($second) / count($second);
    }

    /**
     * Largest minus smallest average of the series that have readings (b_rep, b_od, b_int, b_l).
     */
    public static function spreadOfAverages($series): ?float
    {
        $averages = [];
        foreach ((array) $series as $values) {
            $numbers = array_filter(array_map([self::class, 'number'], array_values((array) $values)), fn ($v) => $v !== null);
            if ($numbers) {
                $averages[] = array_sum($numbers) / count($numbers);
            }
        }
        return count($averages) >= 2 ? max($averages) - min($averages) : null;
    }

    /** Sample standard deviation (Excel STDEV) */
    public static function stdev(array $values): ?float
    {
        $n = count($values);
        if ($n < 2) {
            return null;
        }
        $mean = array_sum($values) / $n;
        return sqrt(array_sum(array_map(fn ($v) => ($v - $mean) ** 2, $values)) / ($n - 1));
    }
}
