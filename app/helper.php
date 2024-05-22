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
