<?php

declare(strict_types=1);

/*
 * Copyright (c) Rasso Hilber
 * https://rassohilber.com
 */

namespace Hirasso\Attr;

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
 *      class?: string|array<int|string, string|int|bool|null>,
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

/**
 * Convert an object or array to JSON that's safe to be used inside a HTMLElement attribute
 */
function jsonAttr(array|object|null|false $value = null): string
{
    return Attr::jsonAttr($value ?? null);
}
