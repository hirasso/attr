<?php

declare(strict_types=1);

/*
 * Copyright (c) Rasso Hilber
 * https://rassohilber.com
 */

use Hirasso\Attr\Builder;

if (!\function_exists('attr')) {
    /**
     * Create a fluent attribute builder, optionally pre-filled with attributes.
     *
     * Only declared if no other global `attr()` exists (e.g. Kirby's).
     * Use `Hirasso\Attr\attr()` to always get this package's version.
     *
     * Usage:
     *   // Array syntax (original)
     *   attr(['type' => 'button', 'class' => 'btn'])
     *
     *   // Fluent syntax
     *   attr()->set('type', 'button')->class('btn')
     *
     *   // Combined - array + chaining
     *   attr(['type' => 'button'])->class('active', when: $isActive)
     *
     * @param array{
     *      class?: string|array<int|string, string|int|bool|null>,
     *      style?: string|array<string, string|int|float|false|null>,
     * }|array<string, string|int|float|bool|null>|null $attributes
     */
    function attr(?array $attributes = null): Builder
    {
        return Hirasso\Attr\attr($attributes);
    }
}

if (!\function_exists('jsonAttr')) {
    /**
     * Convert an object or array to JSON that's safe to be used inside a HTMLElement attribute
     *
     * Only declared if no other global `jsonAttr()` exists.
     * Use `Hirasso\Attr\jsonAttr()` to always get this package's version.
     */
    function jsonAttr(array|object|null|false $value = null): string
    {
        return Hirasso\Attr\jsonAttr($value);
    }
}
