<?php

/*
 * Copyright (c) Rasso Hilber
 * https://rassohilber.com
 */

declare(strict_types=1);

namespace Hirasso\Attr;

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

        return $attrs === []
            ? ''
            : ' '.\implode(' ', $attrs).' ';
    }

    /**
     * Validate $attrs before transforming them
     */
    private static function validate(array $attrs): void
    {
        if (\count(\array_filter(\array_keys($attrs), fn ($key) => \is_int($key))) > 0) {
            throw new InvalidArgumentException('All attribute keys must be strings');
        }

        foreach ($attrs as $key => $value) {
            if (! \is_array($value)) {
                continue;
            }
            if (! \in_array($key, ['class', 'style'])) {
                throw new InvalidArgumentException("Only 'class' and 'style' can contain an array");
            }

            if ($key === 'style' && \count(\array_filter(\array_keys($value), fn ($k) => \is_int($k))) > 0) {
                throw new InvalidArgumentException('All attribute keys must be strings');
            }

            if ($key === 'style') {
                if (\count(\array_filter($value, fn ($nested) => $nested === true)) > 0) {
                    throw new InvalidArgumentException('Nested style properties must never be true');
                }
            }

            if ($key === 'class') {
                if (\count(\array_filter($value, fn ($nestedValue, $nestedKey) => \is_string($nestedKey) && \is_string($nestedValue), ARRAY_FILTER_USE_BOTH)) > 0) {
                    throw new InvalidArgumentException("Values for the 'class' array may not be strings");
                }
                if (\count(\array_filter($value, fn ($nestedValue, $nestedKey) => \is_int($nestedKey) && ! \is_string($nestedValue), ARRAY_FILTER_USE_BOTH)) > 0) {
                    throw new InvalidArgumentException("Numeric keys for the 'class' array must have string values");
                }
            }

            if (\count(\array_filter($value, fn ($nestedValue) => \is_array($nestedValue))) > 0) {
                throw new InvalidArgumentException("Nested array provided for for $key");
            }
        }
    }

    /**
     * Transform an $attrs array into an array of attribute strings
     *
     * @return string[]
     */
    private static function transform(array $attrs): array
    {
        $mapped = [];
        foreach ($attrs as $key => $value) {
            $mapped[$key] = match (true) {
                /** the key is 'style', the value is an array */
                $key === 'style' && \is_array($value) => self::arrayToStyleString($value),
                /** the key is 'class', the value is an array */
                $key === 'class' && \is_array($value) => self::arrayToClassList($value),
                /** the value is a string */
                \is_string($value) => self::encode($value),
                default => $value
            };
        }

        $filtered = \array_filter($mapped, fn ($value) => ! self::isNullOrFalse($value));

        $result = [];
        foreach ($filtered as $key => $value) {
            /** boolean attributes don't need a value */
            if ($value === true) {
                $result[$key] = $key;
            } else {
                $result[$key] = "$key=\"$value\"";
            }
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
        $values = \array_filter($value, fn ($v, $k) => \is_int($k) ? \is_string($v) : ! self::isNullOrFalse($v), ARRAY_FILTER_USE_BOTH);

        if ($values === []) {
            return null;
        }

        $classList = \array_map(fn ($v, $k) => \is_int($k) ? $v : $k, $values, \array_keys($values));
        $classList = \implode(' ', \array_unique($classList));

        return self::encode(\trim($classList));
    }

    /**
     * Create a css style string from an associative array
     */
    private static function arrayToStyleString(
        array $arr
    ): ?string {
        $directives = \array_filter($arr, fn ($value) => $value !== null && $value !== false);

        if ($directives === []) {
            return null;
        }

        $mapped = [];
        foreach ($directives as $property => $value) {
            $mapped[] = self::encode("$property: ".(string) $value);
        }

        return \implode('; ', $mapped);
    }

    /**
     * Convert entities while preserving already-encoded entities
     */
    private static function encode(
        string $html
    ): string {
        return \htmlentities(
            string: $html,
            flags: ENT_QUOTES,
            encoding: 'UTF-8',
            double_encode: false
        );
    }

    /**
     * Convert an array or object to a JSON string that's safe to be used in an attribute
     */
    public static function jsonAttr(array|object|null|false $value): string
    {
        if (empty($value)) {
            return '';
        }

        $json = \json_encode(
            value: $value,
            flags: JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR
        );

        return \htmlspecialchars(
            string: $json,
            flags: ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE,
            encoding: 'UTF-8',
            double_encode: false
        );
    }
}
