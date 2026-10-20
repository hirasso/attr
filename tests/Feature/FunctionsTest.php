<?php

declare(strict_types=1);

use Hirasso\Attr\Builder;

use function Hirasso\Attr\attr;
use function Hirasso\Attr\jsonAttr;

\test('exposes a namespaced attr() function', function () {
    \expect(attr())->toBeInstanceOf(Builder::class);
    \expect((string) attr(['class' => 'foo']))->toBe(' class="foo" ');
    \expect((string) attr(['type' => 'button'])->class('btn'))->toBe(' type="button" class="btn" ');
});

\test('exposes a namespaced jsonAttr() function', function () {
    \expect(jsonAttr(['foo' => 'bar']))->toBe('{&quot;foo&quot;:&quot;bar&quot;}');
    \expect(jsonAttr())->toBe(\jsonAttr());
});

\test('global helpers delegate to the namespaced functions', function () {
    $attributes = ['class' => ['foo', 'bar' => true], 'data-baz' => true];

    \expect((string) \attr($attributes))->toBe((string) attr($attributes));
    \expect(\jsonAttr(['foo' => 'bar']))->toBe(jsonAttr(['foo' => 'bar']));
});
