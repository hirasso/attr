<?php

test('should generate an attribute string', function () {
    $result = attr(['foo' => 'bar']);
    expect($result)->toBe(' foo="bar" ');
});

test('should strip attributes that are exactly false or null', function () {
    $result = attr([
        'tabindex' => '0',
        'data-value' => 0,
        'isFalse' => false,
        'isNull' => null,
    ]);
    expect($result)->toBe(' tabindex="0" data-value="0" ');
});

test('should throw when provided with a list like attr(["foo", "bar", ...])', function () {
    attr(['foo', 'bar']);
})->throws(InvalidArgumentException::class);

test('should throw when provided with a nested *list* for the "style" attribute', function () {
    // @phpstan-ignore argument.type
    attr(['style' => ['foo', 'bar']]);
})->throws(InvalidArgumentException::class);

test('should throw when provided with a nested *list* any other attribute', function () {
    attr(['foo' => ['foo', 'bar']]);
})->throws(InvalidArgumentException::class);

test('should handle style strings', function () {
    $result = attr(['style' => 'backround: red;']);
    expect($result)->toBe(' style="backround: red;" ');
});

test('should handle style arrays', function () {
    $result = attr([
        'style' => [
            'background' => 'red',
            '--custom-color' => 'blue'
        ]
    ]);
    expect($result)->toBe(' style="background: red; --custom-color: blue;" ');
});
