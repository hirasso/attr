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
     *
     * @param  mixed[]  $attributes
     */
    public static function attr(array $attributes): string
    {
        $attrs = collect($attributes);

        self::validate($attrs);

        $attrs = self::transform($attrs);

        return $attrs->isEmpty()
            ? ''
            : ' '.$attrs->join(' ').' ';
    }

    /**
     * Validate $attrs before transforming them
     */
    private static function validate(Collection $attrs): Collection
    {
        if ($attrs->keys()->some(fn ($key) => is_int($key))) {
            throw new InvalidArgumentException('All attribute keys must be strings');
        }

        $attrs->each(function ($value, $key) {
            if (! is_array($value)) {
                return;
            }
            if (! in_array($key, ['class', 'style'])) {
                throw new InvalidArgumentException("Only 'class' and 'style' can contain an array");
            }

            $nestedAttrs = collect($value);

            if ($nestedAttrs->keys()->some(fn ($key) => is_int($key))) {
                throw new InvalidArgumentException('All attribute keys must be strings');
            }

            if ($key === 'style') {
                if (collect($value)->contains(fn ($nested) => $nested === true)) {
                    throw new InvalidArgumentException('Nested style properties must never be true');
                }
            }

            if ($key === 'class') {
                if (collect($value)->contains(fn ($nestedValue) => is_string($nestedValue))) {
                    throw new InvalidArgumentException("Values for the 'class' array may not be strings");
                }
            }

            if (collect($value)->contains(fn ($nestedValue) => is_array($nestedValue))) {
                throw new InvalidArgumentException("Nested array provided for for $key");
            }
        });

        return $attrs;
    }

    /**
     * Transform an $attrs collection into an attribute string
     */
    private static function transform(Collection $attrs): Collection
    {
        return $attrs->map(fn (array|string|bool|null|int $value, string $key) => match (true) {
            /** the key is 'style', the value is an array */
            $key === 'style' && is_array($value) => self::arrayToStyleString($value),
            /** the key is 'class', the value is an array */
            $key === 'class' && is_array($value) => self::arrayToClassList($value),
            /** the value is a string */
            is_string($value) => self::sanitizeStringValue($value),
            default => $value
        })
            ->filter(fn ($value) => ! self::isEmptyValue($value))
            ->map(function (string|null|true|int $value, string $key) {
                /** boolean attributes don't need a value */
                if ($value === true) {
                    return $key;
                }

                return "$key=\"$value\"";
            });
    }

    /**
     * Check if a value is exactly null or false or an empty string
     */
    private static function isEmptyValue(mixed $value)
    {
        return $value === null || $value === false || $value === '';
    }

    /**
     * Convert a class array to a string
     */
    private static function arrayToClassList(array $value): ?string
    {
        $values = collect($value)
            ->filter(fn ($value) => ! self::isEmptyValue($value));

        if ($values->isEmpty()) {
            return null;
        }
        $classList = $values->keys()->unique()->join(' ');

        return self::sanitizeStringValue($classList);
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

        /** escape the value */
        return self::safeHtmlEntities($value);
    }

    /**
     * Create a css style string from an associative array
     */
    private static function arrayToStyleString(
        array $arr
    ): string {
        $directives = collect($arr);

        return $directives
            ->reject(fn ($value) => $value === null || $value === false)
            ->map(function ($value, $property) {
                $property = self::sanitizeStringValue($property);
                $value = self::sanitizeStringValue((string) $value);

                return "$property: $value";
            })
            ->join('; ');
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
    public static function jsonAttr(array|object|null|false $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        return self::safeHtmlEntities(json_encode($value, JSON_NUMERIC_CHECK));
    }
}
