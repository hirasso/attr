<?php

/*
 * Copyright (c) Rasso Hilber
 * https://rassohilber.com
 */

declare(strict_types=1);

namespace Hirasso\Attr\Internal;

/**
 * @internal
 */
final class Arr
{
    /**
     * Check if any element in the array matches the callback.
     *
     * @param  array<array-key, mixed>  $array
     * @param  callable(mixed $value, array-key $key): bool  $callback
     */
    public static function some(array $array, callable $callback): bool
    {
        foreach ($array as $key => $value) {
            if ($callback($value, $key)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Map over an array, preserving keys, with access to both value and key.
     *
     * @param  array<array-key, mixed>  $array
     * @param  callable(mixed $value, array-key $key): mixed  $callback
     * @return array<array-key, mixed>
     */
    public static function mapWithKeys(array $array, callable $callback): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            $result[$key] = $callback($value, $key);
        }

        return $result;
    }
}
