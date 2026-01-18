<?php

/*
 * Copyright (c) Rasso Hilber
 * https://rassohilber.com
 */

use Hirasso\Attr\Attr;
use Hirasso\Attr\Builder;

if (! \function_exists('attr')) {

    /**
     * Create a fluent attribute builder, optionally pre-filled with attributes.
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
     *      class?: string|array<string, int|bool|null>,
     *      style?: string|array<string, string|int|float|false|null>,
     * }|array<string, string|int|float|bool|null>|null $attributes
     */
    function attr(?array $attributes = null): Builder
    {
        $builder = Builder::make();

        if ($attributes !== null) {
            $builder->merge($attributes);
        }

        return $builder;
    }
}

if (! \function_exists('jsonAttr')) {

    /**
     * Convert an object or array to JSON that's safe to be used inside a HTMLElement attribute
     */
    function jsonAttr(array|object|null|false $value = null): string
    {
        return Attr::jsonAttr($value ?? null);
    }
}
