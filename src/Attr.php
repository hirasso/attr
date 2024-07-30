<?php
/*
 * Copyright (c) Rasso Hilber
 * https://rassohilber.com
 */

namespace Hirasso\Attr;

class Attr
{
    /**
     * Converts an array of conditional attributes into a string of HTMLElement attributes.
     */
    public static function attr(
        array $_attrs,
        bool $debug = false
    ): ?string {
        $attrs = [];

        foreach ($_attrs as $name => $value) {
            $value = $name === 'style'
                ? self::array_to_styles($value)
                : self::parse_attribute_value($value);

            $attrs[$name] = self::sanitize_attribute_value($value);
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
    private static function parse_attribute_value(array|string|bool|null $value): string|bool|null
    {
        /** Bail early if the value is not an array */
        if (!is_array($value)) {
            return $value;
        }

        if (!self::is_associative_array($value)) {
            throw new \Exception('$value has to be an associative array or string');
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
    private static function sanitize_attribute_value(mixed $value): mixed
    {
        /** Only touch strings */
        if (!is_string($value)) {
            return $value;
        }
        /** trim whitespace */
        $value = trim($value);
        /** remove double spaces and line breaks */
        $value = preg_replace('/\s+/', ' ', $value);
        /** convert to entities */
        return self::htmlentities2($value);
    }

    /**
     * Checks whether an array is associative or not
     *
     * <code>
     * $array = ['a', 'b', 'c'];
     *
     * Utils::is_associative_array($array);
     * // returns: false
     *
     * $array = ['a' => 'a', 'b' => 'b', 'c' => 'c'];
     *
     * Utils::is_associative_array($array);
     * // returns: true
     * </code>
     */
    public static function is_associative_array(array $array): bool
    {
        return ctype_digit(implode('', array_keys($array))) === false;
    }

    /**
     * Create a css style string from an associative array
     */
    public static function array_to_styles(array $directives): string
    {
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
     *
     * @link https://www.php.net/htmlentities Borrowed from the PHP Manual user notes.
     *
     * @since 1.2.2
     *
     * @param string $text The text to be converted.
     * @return string Converted text.
     */
    function htmlentities2($text)
    {
        $translation_table = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);

        $translation_table[chr(38)] = '&';

        return preg_replace('/&(?![A-Za-z]{0,4}\w{2,3};|#[0-9]{2,3};)/', '&amp;', strtr($text, $translation_table));
    }
}
