<?php

declare(strict_types=1);

use Hirasso\Attr\Builder;

use function Hirasso\Attr\attr;
use function Hirasso\Attr\jsonAttr;

\test('namespaced attr() returns a builder', function () {
    \expect(attr())->toBeInstanceOf(Builder::class);
    \expect(attr(['type' => 'button'])->class('btn')->toString())->toBe(' type="button" class="btn" ');
});

\test('namespaced attr() matches the global helper', function () {
    $attributes = ['type' => 'button', 'class' => ['btn', 'active' => true]];
    \expect(attr($attributes)->toString())->toBe(\attr($attributes)->toString());
});

\test('namespaced jsonAttr() matches the global helper', function () {
    $value = ['foo' => '<b>bar</b>'];
    \expect(jsonAttr($value))->toBe(\jsonAttr($value));
});
