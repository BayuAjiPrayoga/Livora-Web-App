<?php

namespace App\Helpers;

class NumberToWords
{
    private static $ones = [
        '', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan',
        'sepuluh', 'sebelas', 'dua belas', 'tiga belas', 'empat belas', 'lima belas',
        'enam belas', 'tujuh belas', 'delapan belas', 'sembilan belas'
    ];

    public static function convert($number)
    {
        if ($number == 0) {
            return 'nol';
        }

        if ($number < 0) {
            return 'minus ' . self::convert(abs($number));
        }

        $words = '';

        // Triliun
        if ($number >= 1000000000000) {
            $trillions = floor($number / 1000000000000);
            $words .= self::convert($trillions) . ' triliun ';
            $number %= 1000000000000;
        }

        // Miliar
        if ($number >= 1000000000) {
            $billions = floor($number / 1000000000);
            $words .= self::convert($billions) . ' miliar ';
            $number %= 1000000000;
        }

        // Juta
        if ($number >= 1000000) {
            $millions = floor($number / 1000000);
            $words .= self::convert($millions) . ' juta ';
            $number %= 1000000;
        }

        // Ribu
        if ($number >= 1000) {
            $thousands = floor($number / 1000);
            if ($thousands == 1) {
                $words .= 'seribu ';
            } else {
                $words .= self::convert($thousands) . ' ribu ';
            }
            $number %= 1000;
        }

        // Ratus
        if ($number >= 100) {
            $hundreds = floor($number / 100);
            if ($hundreds == 1) {
                $words .= 'seratus ';
            } else {
                $words .= self::$ones[$hundreds] . ' ratus ';
            }
            $number %= 100;
        }

        // Puluhan dan Satuan
        if ($number > 0) {
            if ($number < 20) {
                $words .= self::$ones[$number];
            } else {
                $tens = floor($number / 10);
                $ones = $number % 10;
                
                $words .= self::$ones[$tens] . ' puluh';
                if ($ones > 0) {
                    $words .= ' ' . self::$ones[$ones];
                }
            }
        }

        return trim($words);
    }
}
