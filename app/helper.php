<?php

// menambahkan method helper untuk mengganti placeholder
if (!function_exists('replacePlaceholder')) {
    function replacePlaceholder(string $template, array $data): string
    {
        foreach ($data as $key => $value) {
            $template = str_replace("[" . $key . "]", $value, $template);
        }
        return $template;
    }
}

if (!function_exists('currency')) {
    function currency($amount): string
    {
        return  'Rp. ' . number_format($amount, 0, ',', '.');
    }
}

if (!function_exists('convertSpeed')) {
    function convertSpeed($value)
    {
        if ($value >= 1000000) {
            return round($value / 1000000, 2) . ' Mbps';
        } elseif ($value >= 1000) {
            return round($value / 1000, 2) . ' kbps';
        } else {
            return $value . ' bps';
        }
    }
}
