<?php
/*
 * Copyright (c) Rasso Hilber
 * https://rassohilber.com
 */
declare(strict_types=1);

namespace Hirasso\Attr;

use Illuminate\Support\Collection;
use InvalidArgumentException;

final readonly class Attr
{
    /**
     * Convert an array of conditional attributes into a string of HTMLElement attributes.
     */
    public static function attr(array $attributes): string
    {
        $attrs = collect($attributes);

        self::validateKeys($attrs);

        $attrs->each(function ($value, $key) {
            if (!is_array($value)) {
                return;
            }
            if (!in_array($key, ['class', 'style'])) {
                throw new InvalidArgumentException("Only 'class' and 'style' can contain an array");
            }
            if (array_is_list($value)) {
                throw new InvalidArgumentException("Non-associative array provided for $key");
            }
            if (collect($value)->contains(fn($nestedValue) => is_array($nestedValue))) {
                throw new InvalidArgumentException("Nested array provided for for $key");
            }
        });


        $attrs = $attrs->map(fn(array|string|bool|null|int $value, string $key) => match (true) {
            /** the key is 'style', the value is an array */
            $key === 'style' && is_array($value) => self::arrayToStyleString($value),
            /** the key is 'class', the value is an array */
            $key === 'class' && is_array($value) => self::arrayToClassString($value),
            /** the value is an string */
            is_string($value) => self::sanitizeStringValue($value),
            default => $value
        })
            ->filter(fn($value) => !self::isEmptyValue($value))
            ->map(function (string|null|bool|int $value, string $key) {
                /** boolean attributes don't need a value */
                if ($value === true || $value === '') {
                    return $key;
                }
                return "$key=\"$value\"";
            })
            ->join(" ");

        return " $attrs ";
    }

    /**
     * Validate that all attribute keys are strings
     */
    private static function validateKeys(Collection $attributes)
    {
        if ($attributes->keys()->some(fn($key) => is_int($key))) {
            throw new InvalidArgumentException('All attribute keys must be strings');
        }
    }

    /**
     * Check if a value is exactly null or false
     */
    private static function isEmptyValue(mixed $value)
    {
        return $value === null || $value === false;
    }

    /**
     * Convert a class array to a string
     */
    private static function arrayToClassString(array $value): ?string
    {
        $values = collect($value)
            ->filter(fn($value) => !self::isEmptyValue($value));

        self::validateKeys($values);

        return $values->isEmpty()
            ? null
            : $values->keys()->unique()->join(' ');
    }

    /**
     * Sanitize a value for an attribute
     */
    private static function sanitizeStringValue(string $value): string
    {
        /** trim whitespace */
        $value = trim($value);
        /** remove double spaces and line breaks */
        $value = preg_replace('/\s+/', ' ', $value);
        /** convert to entities */
        return self::safeHtmlEntities($value);
    }

    /**
     * Create a css style string from an associative array
     */
    private static function arrayToStyleString(
        array $value
    ): string {
        $directives = collect($value);

        self::validateKeys($directives);

        return $directives
            ->reject(fn($value) => $value === null || $value === false)
            ->map(fn($value, $property) => "$property: $value;")
            ->join(" ");
    }

    /**
     * Converts entities, while preserving already-encoded entities.
     * Borrowed from the WordPress core function
     *
     * @see https://developer.wordpress.org/reference/functions/htmlentities2/
     */
    private static function safeHtmlEntities(
        string $text
    ): string {
        $translationTable = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);
        $translationTable[chr(38)] = '&';

        return preg_replace(
            pattern: '/&(?![A-Za-z]{0,4}\w{2,3};|#[0-9]{2,3};)/',
            replacement: '&amp;',
            subject: strtr($text, $translationTable)
        );
    }

    /**
     * Convert an array or object to a JSON string that's safe to be used in an attribute
     */
    public static function jsonAttr(mixed $value = ''): string
    {
        if (empty($value)) {
            return '';
        }
        return self::safeHtmlEntities(json_encode($value, JSON_NUMERIC_CHECK));
    }
}
