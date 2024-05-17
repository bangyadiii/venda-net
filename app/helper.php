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
