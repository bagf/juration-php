<?php

namespace Juration;

class Juration
{
    const UNITS = [
        'seconds' => [
            'patterns' => ['second', 'seconds', 'sec', 'secs', 's'],
            'value' => 1,
            'formats' => [
                'chrono' => '',
                'micro' => 's',
                'short' => 'sec',
                'long' => 'second'
            ]
        ],
        'minutes' => [
            'patterns' => ['minute', 'minutes', 'min', 'mins', 'm'],
            'value' => 60,
            'formats' => [
                'chrono' => ':',
                'micro' => 'm',
                'short' => 'min',
                'long' => 'minute'
            ]
        ],
        'hours' => [
            'patterns' => ['hour', 'hours', 'hr', 'hrs', 'h', 'hs'],
            'value' => 3600,
            'formats' => [
                'chrono' => ':',
                'micro' => 'h',
                'short' => 'hr',
                'long' => 'hour'
            ]
        ],
        'days' => [
            'patterns' => ['day', 'days', 'dy', 'dys', 'd', 'ds'],
            'value' => 86400,
            'formats' => [
                'chrono' => ':',
                'micro' => 'd',
                'short' => 'day',
                'long' => 'day'
            ]
        ],
        'weeks' => [
            'patterns' => ['week', 'weeks', 'wk', 'wks', 'w', 'ws'],
            'value' => 604800,
            'formats' => [
                'chrono' => ':',
                'micro' => 'w',
                'short' => 'wk',
                'long' => 'week',
            ]
        ],
        'months' => [
            'patterns' => ['month', 'months', 'mon', 'mons', 'mo', 'mos', 'mth', 'mths'],
            'value' => 2628000,
            'formats' => [
                'chrono' => ':',
                'micro' => 'm',
                'short' => 'mth',
                'long' => 'month',
            ]
        ],
        'years' => [
            'patterns' => ['year', 'years', 'yr', 'yrs', 'y', 'ys'],
            'value' => 31536000,
            'formats' => [
                'chrono' => ':',
                'micro' => 'y',
                'short' => 'yr',
                'long' => 'year',
            ],
        ]
    ];

    /**
     * Returns multiple strpos results if the needle can be match to haystack. One caveat: strposes will only match
     * needles that end with a space in the haystack or when they are at the end of the haystack.
     * @param string $haystack
     * @param string $needle
     * @return array
     */
    protected static function strposes($haystack, $needle)
    {
        $needleLength = strlen($needle); // length of needle will be added to $advance after each successful match
        $matches = []; // the strpos results to be gathered
        $advance = 0;
        $mutableHaystack = $haystack; // this variable will be reduced as we advance in the loop below
        do {
            $pos = strpos($mutableHaystack, $needle); // where is the needle relative to mutableHaystack
            $stillMore = ($pos !== false); // can anything still be matched
            if ($stillMore) {
                $advancement = $pos + $needleLength; // calculate the advancement of the mutableHaystack string
                // last char can only be space or the end of the haystack
                $tailingChar = substr($mutableHaystack, $advancement, 1);
                if ($tailingChar == ' ' || $advancement >= strlen($mutableHaystack)) {
                    $matches[] = $pos + $advance; // where the needle is relative to the haystack
                }
                $advance += $advancement;
                $mutableHaystack = substr($mutableHaystack, $advancement); // advance haystack
            }
        } while ($stillMore);
        return $matches;
    }

    protected static function pluralize($count, $singular)
    {
        return $count == 1 ? $singular : $singular . "s";
    }

    protected static function padLeft($s, $c, $n)
    {
        if (!$s || !$c || strlen($s) >= $n) {
            return $s;
        }
        $max = ($n - strlen($s)) / strlen($c);
        for ($i = 0; $i < $max; $i++) {
            $s = $c + $s;
        }
        return $s;
    }

    public static function stringify($seconds, $format = 'short', $units = null)
    {
        if (!is_numeric($seconds)) {
            throw new Exception(__CLASS__ . "::" . __METHOD__ . "(): Unable to stringify a non-numeric value");
        }

        if (!in_array($format, ['mirco', 'short', 'long'])) {
            throw new Exception("juration.stringify(): format cannot be '{$format}', and must be either 'micro', 'short', or 'long'");
        }
        $unitTypes = ['years', 'months', 'days', 'hours', 'minutes', 'seconds'];
        $values = [];
        $remaining = $seconds;
        $activeUnits = 0;
        foreach ($unitTypes as $unitType) {
            $unit = static::UNITS[$unitType];
            $val = floor($remaining / $unit['value']);
            if ($val > 0 || $activeUnits > 0) {
                $activeUnits++;
            }

            if ($format === 'micro' || $format === 'chrono') {
                $val .= $unit['formats'][$format];
            } else {
                $val .= ' ' . static::pluralize($val, $unit['formats'][$format]);
            }
            $remaining = $remaining % $unit['value'];
            $values[] = $val;
        }
        $output = '';
        foreach ($values as $i => $value) {
            if (substr($value, 0, 1) !== "0" && $format != 'chrono') {
                $output .= $value . ' ';
            } else if ($format == 'chrono') {
                $output .= static::padLeft($value + '', '0', $i == strlen($values) - 1 ? 2 : 3);
            }
        }

        $output = trim($output);
        if (substr($output, 0, 3) == '00:') {
            $output = substr($output, 3);
        }

        if (substr($output, 0, 1) == '0') {
            $output = substr($output, 1);
        }

        return $output;
    }

    /**
     * @param string $string
     * @return int
     * @throws \Juration\Exception
     */
    public static function parse($string)
    {
        $time = 0; // time from string in seconds
        $found = []; // positions in $strings where time was found
        foreach (static::UNITS as $unit) {
            foreach ($unit['patterns'] as $pattern) {
                // get positions where pattern is present but exclude positions that were matched and accounted for
                $positions = array_diff(static::strposes($string, $pattern), $found);
                foreach ($positions as $pos) {
                    $number = ''; // start of string representing the number before our matched pattern
                    for ($i = ($pos - 1); $i > -1; $i--) { // iterate backwards from our matched pattern's start pos
                        $c = substr($string, $i, 1); // get single character
                        if ($c == ' ' || $c == "\t") {
                            continue; // skip space or tab characters as they're never numbers anyway
                        }
                        if (!is_numeric($c) && $c != '.') {
                            break; // break loop if the character is not a numbers and not a decimal point
                        }
                        $number = $c . $number; // build the number string by pre-appending the character
                    }
                    if (!strlen($number)) {
                        continue; // try another matching position if no number string was built
                    }
                    if (!is_numeric($number)) {
                        // throw exception when the number string cannot be a number
                        throw new Exception(__CLASS__ . "::" . __METHOD__ . "(): Unable to parse: a falsey value");
                    }
                    $found[] = $pos; // add position to found array so it doesn't get matched again potentially
                    $time += intval(floatval($number) * $unit['value']); // calculate time in seconds
                }
            }
        }
        return $time;
    }
}
