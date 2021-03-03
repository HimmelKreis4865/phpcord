<?php

namespace phpcord\utils;

use DateTime;
use DateTimeInterface;
use DateTimeZone;
use Exception;
use InvalidArgumentException;
use function date;
use function is_int;
use function is_numeric;
use function is_string;

final class Math {
    /**
     * Returns the creation date of a snowflake calculated with a little peace of math
     *
     * @api
     *
     * @param string|int $snowflake
     * @param string $timezone
     * @param string $format
     *
     * @return string
     *
     * @throws Exception
     */
    public static function getCreationDate($snowflake, string $timezone = "Europe/London", string $format = "H:i:s d.m.Y"): string {
        if (is_string($snowflake) and is_numeric($snowflake)) $snowflake = intval($snowflake);
        if (!is_int($snowflake) or $snowflake < 4194304) throw new InvalidArgumentException("Cannot get the creation date of an invalid snowflake!");
        $timestamp = ($snowflake / 4194304 + 1420070400000);

        $date = new DateTime(date(DateTimeInterface::ISO8601, $timestamp / 1000));

        $date->setTimezone(new DateTimeZone($timezone));

        return $date->format($format);
    }
}