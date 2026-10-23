<?php

declare(strict_types=1);

/*
 * Copyright (c) Rasso Hilber
 * https://rassohilber.com
 */

use Hirasso\Attr\Builder;

if (!\function_exists('attr')) {
    /**
     * Global alias for `Hirasso\Attr\attr()`
     *
     * @param array{
     *      class?: string|array<int|string, string|int|bool|null>,
     *      style?: string|array<string, string|int|float|false|null>,
     * }|array<string, string|int|float|bool|null>|null $attributes
     */
    function attr(?array $attributes = null): Builder
    {
        return \Hirasso\Attr\attr($attributes);
    }
}

if (!\function_exists('jsonAttr')) {
    /**
     * Global alias for `Hirasso\Attr\jsonAttr()`
     */
    function jsonAttr(array|object|null|false $value = null): string
    {
        return \Hirasso\Attr\jsonAttr($value);
    }
}
