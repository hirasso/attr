<?php

test('generates an attribute string', function () {
    $result = attr(['class' => 'border border-red bg-black']);
    expect($result)->toBe(' class="border border-red bg-black" ');
});

test('strips attributes that are exactly false or null', function () {
    $result = attr([
        'tabindex' => '0',
        'data-value' => 0,
        'style' => false,
        'class' => null,
    ]);
    expect($result)->toBe(' tabindex="0" data-value="0" ');
});

test('handles strings for style and class', function () {
    $result = attr([
        'class' => 'border border-red',
        'style' => 'color: black;',
    ]);
    expect($result)->toBe(' class="border border-red" style="color: black;" ');
});

test('handles arrays for style and class', function () {
    $result = attr([
        'class' => [
            'border border-red' => true,
            'hidden' => false,
        ],
        'style' => [
            'color' => 'black',
            'background' => 'white',
            'border' => false,
        ],
    ]);
    expect($result)->toBe(' class="border border-red" style="color: black; background: white;" ');
});

test('throws when provided with a list like attr(["foo", "bar", ...])', function () {
    // @phpstan-ignore argument.type
    attr(['foo', 'bar']);
})->throws(InvalidArgumentException::class);

test('throws when provided with a non-associative array for the "style" attribute', function () {
    // @phpstan-ignore argument.type
    attr(['style' => ['foo', 'bar']]);
})->throws(InvalidArgumentException::class);

test('throws when provided with a non-associative array for the "class" attribute', function () {
    // @phpstan-ignore argument.type
    attr(['class' => ['foo', 'bar']]);
})->throws(InvalidArgumentException::class);

test('throws when provided with a nested array for any attribute', function () {
    // @phpstan-ignore argument.type
    attr([
        'class' => [
            'foo' => ['bar'],
        ],
    ]);
})->throws(InvalidArgumentException::class);

test('throws when provided with an array for any other attribute then "style" or "class"', function () {
    attr(['foo' => ['foo' => 'bar']]);
})->throws(InvalidArgumentException::class);

test('handles style strings', function () {
    $result = attr(['style' => 'backround: red;']);
    expect($result)->toBe(' style="backround: red;" ');
});
