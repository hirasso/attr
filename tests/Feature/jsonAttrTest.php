<?php

test('generates a valid json attribute from an array', function () {
    $result = jsonAttr([
        'foo' => 'bar',
        'bool' => true,
        'int' => 2,
        'float' => 1.2,
        'numeric' => '2.5',
        'xss' => '<script>alert("test")</script>',
        'amp' => 'a & b',
        'apos' => "it's",
    ]);
    expect($result)->toBe('{"foo":"bar","bool":true,"int":2,"float":1.2,"numeric":"2.5","xss":"\u003Cscript\u003Ealert(\u0022test\u0022)\u003C/script\u003E","amp":"a \u0026 b","apos":"it\u0027s"}');
});

test('generates an escaped json attribute from an object', function () {
    $result = jsonAttr((object) [
        'foo' => 'bar',
        'bool' => true,
        'int' => 2,
        'float' => 1.2,
        'numeric' => '2.5',
        'xss' => '<script>alert("test")</script>',
        'amp' => 'a & b',
        'apos' => "it's",
    ]);
    expect($result)->toBe('{"foo":"bar","bool":true,"int":2,"float":1.2,"numeric":"2.5","xss":"\u003Cscript\u003Ealert(\u0022test\u0022)\u003C/script\u003E","amp":"a \u0026 b","apos":"it\u0027s"}');
});

test('returns an empty string if the value is empty', function () {
    expect(jsonAttr([]))->toBe('');
    expect(jsonAttr(null))->toBe('');
    expect(jsonAttr(false))->toBe('');
});
