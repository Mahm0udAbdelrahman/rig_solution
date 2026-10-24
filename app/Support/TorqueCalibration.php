<?php

namespace App\Support;

/**
 * Torque wrench calibration results and measurement uncertainty according to ISO 6789-2:2017,
 * following the "Data Analysis" sheet of RS-CAL-P11-F02 Raw data-UNcert-Certificate Torque.xlsx.
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
        $bl = self::spreadOfAverages($details['loading_point'] ?? []);

        $result = [
            'factor' => $factor,
            'brep' => $brep,
            'bod' => $bod,
            'bint' => $bint,
            'bl' => $bl,
            'directions' => [],
        ];

        foreach (self::DIRECTIONS as $direction) {
            if (!in_array($direction, $directions, true)) {
                continue;
            }
            $reference = (array) ($details['reference'][$direction] ?? []);
            $coefficients = [
                self::number($reference['a'] ?? null) ?? 1.0,
                self::number($reference['b'] ?? null) ?? 0.0,
                self::number($reference['c'] ?? null) ?? 0.0,
            ];
            $uRef = self::number($reference['u'] ?? null);

            $rows = [];
            foreach (range(0, self::POINTS - 1) as $point) {
                $xa = $targets[$point] ?? null;
                $readings = array_values(array_filter(
                    array_map([self::class, 'number'], array_values((array) ($details['readings'][$direction][$point] ?? []))),
                    fn ($v) => $v !== null
                ));
                $rows[] = self::point($xa, $readings, $coefficients, $factor, $r, $uRef, $brep, $bod, $bint, $bl);
            }
            $result['directions'][$direction] = $rows;
        }

        return $result;
    }

    /**
     * One calibration point: corrected reference values, errors and the uncertainty budget.
     */
    private static function point(?float $xa, array $readings, array $coefficients, float $factor, ?float $r, ?float $uRef, ?float $brep, ?float $bod, ?float $bint, ?float $bl): array
    {
        $row = ['xa' => $xa, 'xr' => null, 'mean_error' => null, 'w' => null, 'W' => null, 'W_interval' => null, 'readings' => []];
        if ($xa === null || !$readings) {
            return $row;
        }

        [$a, $b, $c] = $coefficients;
        $corrected = array_map(function ($reading) use ($a, $b, $c, $factor) {
            $x = $reading / $factor;
            return ($a * $x + $b * $x ** 2 + $c * $x ** 3) * $factor;
        }, $readings);

        $xr = array_sum($corrected) / count($corrected);
        $errors = [];
        foreach ($corrected as $i => $value) {
            $row['readings'][] = [
                'taken' => $readings[$i],
                'corrected' => $value,
                'error' => $xa - $value,
                'relative_error' => $value != 0 ? ($xa - $value) / $value * 100 : null,
                'deviation' => $value - $xr,
            ];
            if ($value != 0) {
                $errors[] = ($xa - $value) / $value * 100;
            }
        }

        $n = count($corrected);
        $bre = self::stdev($corrected);
        // relative standard uncertainties (%) of the uncertainty budget
        $relative = fn (?float $b, float $divisor) => ($b === null || $xr == 0) ? 0.0 : $b * 100 / ($xr * $divisor);
        $budget = [
            'wmd' => $uRef === null ? 0.0 : $uRef / 2,
            'wr' => $r === null ? 0.0 : $relative($r * 0.5, sqrt(3)),
            'wrep' => $relative($brep === null ? null : $brep * 0.5, sqrt(3)),
            'wod' => $relative($bod === null ? null : $bod * 0.5, sqrt(3)),
            'wint' => $relative($bint === null ? null : $bint * 0.5, sqrt(3)),
            'wl' => $relative($bl === null ? null : $bl * 0.5, sqrt(3)),
            'wre' => $bre === null ? 0.0 : $relative($bre, sqrt($n)),
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
            // ISO 6789-2: W' = |mean relative measurement error| + W
            'W_interval' => $meanError === null ? null : abs($meanError) + 2 * $w,
        ]);
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
