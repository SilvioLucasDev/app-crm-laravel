<?php

if (!function_exists('obfuscate_email')) {
    function obfuscate_email(?string $email = null): string
    {
        if(!$email) {
            return '';
        }

        $split = explode('@', $email);

        if(sizeof($split) != 2) {
            return '';
        }

        $firstPart       = $split[0];
        $qty             = (int) floor(strlen($firstPart) * 0.75);
        $remaining       = strlen($firstPart) - $qty;
        $maskedFirstPart = substr($firstPart, 0, $remaining) . str_repeat('*', $qty);

        $secondPart       = $split[1];
        $qty              = (int) floor(strlen($secondPart) * 0.75);
        $remaining        = strlen($secondPart) - $qty;
        $maskedSecondPart = str_repeat('*', $qty) . substr($secondPart, $remaining * -1, $remaining);

        return $maskedFirstPart . '@' . $maskedSecondPart;
    }
}

if (!function_exists('format_amount_to_save')) {
    function format_amount_to_save(?string $amount = null): int
    {
        if ($amount === null) {
            return 0;
        }

        $amount = floatval($amount);

        return (int) ($amount * 100);
    }
}

if (!function_exists('format_amount_to_show')) {
    function format_amount_to_show(int $amount, $decimal = true): string
    {
        if ($decimal == true) {
            return number_format($amount / 100, 2, ',', '.');
        }

        return (string) ($amount / 100);
    }
}
