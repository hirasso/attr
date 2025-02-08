<?php

/*
 * Copyright (c) Rasso Hilber
 * https://rassohilber.com
 */

use Hirasso\Attr\Attr;

if (! function_exists('attr')) {

    /**
     * Convert an array of conditional attributes into a string of HTMLElement attributes.
     *
     * @param array{
     *      class?: string|array<string, string|int|bool|null>,
     *      style?: string|array<string, string|int|bool|null>,
     * }|array<string, string|int|bool|null> $attributes
     */
    function attr(array $attributes): string
    {
        return Attr::attr($attributes);
    }
}

if (! function_exists('jsonAttr')) {

    /**
     * Convert an object or array to JSON that's safe to be used inside a HTMLElement attribute
     */
    function jsonAttr(array|object|null|false $value): ?string
    {
        return Attr::jsonAttr($value);
    }
}
