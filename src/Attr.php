<?php
/*
 * Copyright (c) Rasso Hilber
 * https://rassohilber.com
 */

namespace Hirasso\Attr;

use InvalidArgumentException;

final readonly class Attr
{
    /**
     * Convert an array of conditional attributes into a string of HTMLElement attributes.
     */
    public static function attr(
        array $_attrs
    ): ?string {
        $attrs = [];

        foreach ($_attrs as $name => $value) {
            $value = $name === 'style' && is_array($value)
                ? self::arrayToStyles($value)
                : self::parseAttributeValue($value);

            $attrs[$name] = self::sanitizeAttributeValue($value);
        }

        $pairs = [];

        foreach (array_filter($attrs) as $attr => $value) {
            $pairs[] = $value === true ? $attr : "$attr=\"$value\"";
        }

        return " " . implode(" ", $pairs);
    }

    /**
     * Parse an attribute value.
     *
     * Arrays will get special treatment:
     *
     *  - remove falsy values
     *  - remove duplicates
     *
     * @throws \Exception
     */
    private static function parseAttributeValue(
        array|string|bool|null $value
    ): string|bool|null {
        /** Bail early if the value is not an array */
        if (!is_array($value)) {
            return $value;
        }

        if (array_is_list($value)) {
            throw new InvalidArgumentException('$value has to be an associative array or string');
        }

        /** Remove falsy values */
        $value = array_filter($value);

        /** Use the remaining keys */
        $tokens = array_keys($value);

        /** Remove duplicates */
        $deduped = array_unique(explode(' ', implode(' ', $tokens)));

        return implode(' ', $deduped);
    }

    /**
     * Sanitize a value for an attribute
     */
    private static function sanitizeAttributeValue(
        mixed $value
    ): mixed {
        /** Only touch strings */
        if (!is_string($value)) {
            return $value;
        }
        /** trim whitespace */
        $value = trim($value);
        /** remove double spaces and line breaks */
        $value = preg_replace('/\s+/', ' ', $value);
        /** convert to entities */
        return self::htmlentitiesAgain($value);
    }

    /**
     * Create a css style string from an associative array
     */
    private static function arrayToStyles(
        array $directives
    ): string {
        $styles = [];
        foreach ($directives as $property => $value) {
            if (in_array($value, [false, null, ""])) {
                continue;
            }
            $styles[] = "$property: $value;";
        }
        return implode(" ", $styles);
    }

    /**
     * Converts entities, while preserving already-encoded entities.
     * Borrowed from the WordPress core function
     *
     * @see https://developer.wordpress.org/reference/functions/htmlentities2/
     */
    private static function htmlentitiesAgain(
        string $text
    ): string {
        $translation_table = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);

        $translation_table[chr(38)] = '&';

        $text = strtr($text, $translation_table);

        return preg_replace(
            pattern: '/&(?![A-Za-z]{0,4}\w{2,3};|#[0-9]{2,3};)/',
            replacement: '&amp;',
            subject: $text
        );
    }

    /**
     * Convert a PHP array or object to a json string that's safe to be used in an attribute
     */
    public static function jsonInAttr(
        mixed $value = ''
    ): string {
        if (empty($value)) {
            return '';
        }
        return self::htmlentitiesAgain(json_encode($value, JSON_NUMERIC_CHECK));
    }
}
