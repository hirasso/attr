<?php

/*
 * Copyright (c) Rasso Hilber
 * https://rassohilber.com
 */

declare(strict_types=1);

namespace Hirasso\Attr;

use Hirasso\Attr\Internal\Arr;
use InvalidArgumentException;

final readonly class Attr
{
    /**
     * Convert an array of conditional attributes into a string of HTMLElement attributes.
     *
     * @param  mixed[]  $attributes
     */
    public static function attr(array $attributes): string
    {
        self::validate($attributes);

        $attrs = self::transform($attributes);

        return $attrs === [] ? '' : ' ' . \implode(' ', $attrs) . ' ';
    }

    /**
     * Validate $attrs before transforming them
     */
    private static function validate(array $attrs): void
    {
        if (Arr::some(\array_keys($attrs), static fn($key) => \is_int($key))) {
            throw new InvalidArgumentException('All attribute keys must be strings');
        }

        foreach ($attrs as $key => $value) {
            if (!\is_array($value)) {
                continue;
            }
            if (!\in_array($key, ['class', 'style'], strict: true)) {
                throw new InvalidArgumentException("Only 'class' and 'style' can contain an array");
            }

            if ($key === 'style') {
                self::validateStyleValue($value);
            }

            if ($key === 'class') {
                self::validateClassValue($value);
            }

            if (Arr::some($value, static fn($nestedValue) => \is_array($nestedValue))) {
                throw new InvalidArgumentException("Nested array provided for for {$key}");
            }
        }
    }

    private static function validateStyleValue(array $value): void
    {
        if (Arr::some(\array_keys($value), static fn($k) => \is_int($k))) {
            throw new InvalidArgumentException('All attribute keys must be strings');
        }

        if (Arr::some($value, static fn($nested) => $nested === true)) {
            throw new InvalidArgumentException('Nested style properties must never be true');
        }
    }

    private static function validateClassValue(array $value): void
    {
        if (Arr::some(
            $value,
            static fn($nestedValue, $nestedKey) => \is_string($nestedKey) && \is_string($nestedValue),
        )) {
            throw new InvalidArgumentException("Values for the 'class' array may not be strings");
        }

        if (Arr::some(
            $value,
            static fn($nestedValue, $nestedKey) => \is_int($nestedKey) && !\is_string($nestedValue),
        )) {
            throw new InvalidArgumentException("Numeric keys for the 'class' array must have string values");
        }
    }

    /**
     * Transform an $attrs array into an array of attribute strings
     *
     * @return list<string>
     */
    private static function transform(array $attrs): array
    {
        $mapped = Arr::mapWithKeys($attrs, static fn($value, $key) => match (true) {
            /** the key is 'style', the value is an array */
            $key === 'style' && \is_array($value) => self::arrayToStyleString($value),
            /** the key is 'class', the value is an array */
            $key === 'class' && \is_array($value) => self::arrayToClassList($value),
            /** the value is a string */
            \is_string($value) => self::encode($value),
            default => $value,
        });

        $filtered = \array_filter($mapped, static fn($value) => !self::isNullOrFalse($value));

        $result = [];
        foreach ($filtered as $key => $value) {
            $result[] = $value === true ? (string) $key : "{$key}=\"{$value}\"";
        }

        return $result;
    }

    /**
     * Check if a value is exactly null or false
     */
    private static function isNullOrFalse(mixed $value): bool
    {
        return $value === null || $value === false;
    }

    /**
     * Convert a class array to a string
     */
    private static function arrayToClassList(array $value): ?string
    {
        $values = \array_filter(
            $value,
            static fn($v, $k) => \is_int($k) ? \is_string($v) : !self::isNullOrFalse($v),
            ARRAY_FILTER_USE_BOTH,
        );

        if ($values === []) {
            return null;
        }

        $classList = \array_map('strval', Arr::mapWithKeys($values, static fn($v, $k) => \is_int($k) ? $v : $k));
        $classList = \implode(' ', \array_unique($classList));

        return self::encode(\trim($classList));
    }

    /**
     * Create a css style string from an associative array
     */
    private static function arrayToStyleString(array $arr): ?string
    {
        $directives = \array_filter($arr, static fn($value) => $value !== null && $value !== false);

        if ($directives === []) {
            return null;
        }

        $mapped = [];
        foreach ($directives as $property => $value) {
            $mapped[] = self::encode("{$property}: " . (string) $value);
        }

        return \implode('; ', $mapped);
    }

    /**
     * Convert entities while preserving already-encoded entities
     */
    private static function encode(string $html): string
    {
        return \htmlentities(string: $html, flags: ENT_QUOTES, encoding: 'UTF-8', double_encode: false);
    }

    /**
     * Convert an array or object to a JSON string that's safe to be used in an attribute
     */
    public static function jsonAttr(array|object|null|false $value): string
    {
        if ($value === null || $value === false || $value === []) {
            return '';
        }

        $json = \json_encode(
            value: $value,
            flags: JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR,
        );

        return \htmlspecialchars(
            string: $json,
            flags: ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE,
            encoding: 'UTF-8',
            double_encode: false,
        );
    }
}
