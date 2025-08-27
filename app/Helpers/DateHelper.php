<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    /**
     * Safely format a date that might be a string or Carbon instance
     */
    public static function safeFormat($date, $format = 'd M Y, H:i')
    {
        if (!$date) {
            return 'N/A';
        }

        try {
            if (is_string($date)) {
                return Carbon::parse($date)->format($format);
            } elseif ($date instanceof Carbon) {
                return $date->format($format);
            } else {
                // Try to convert to string and parse
                return Carbon::parse((string) $date)->format($format);
            }
        } catch (\Exception $e) {
            // If all else fails, return the original value
            return $date;
        }
    }

    /**
     * Format date for display in tables
     */
    public static function tableFormat($date)
    {
        return self::safeFormat($date, 'd M Y, H:i');
    }

    /**
     * Format date for display in forms
     */
    public static function formFormat($date)
    {
        return self::safeFormat($date, 'Y-m-d\TH:i');
    }

    /**
     * Format date for display (date only)
     */
    public static function dateOnly($date)
    {
        return self::safeFormat($date, 'd M Y');
    }
}
