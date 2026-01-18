<?php

test('generates an escaped json attribute from an array', function () {
    $result = jsonAttr(['foo' => 'bar', 'bool' => true, 'int' => 2, 'float' => 1.2, 'numeric' => '2.5']);
    expect($result)->toBe('{&quot;foo&quot;:&quot;bar&quot;,&quot;bool&quot;:true,&quot;int&quot;:2,&quot;float&quot;:1.2,&quot;numeric&quot;:2.5}');
});

test('generates an escaped json attribute from an object', function () {
    $result = jsonAttr((object) ['foo' => 'bar', 'bool' => true, 'int' => 2, 'float' => 1.2, 'numeric' => '2.5']);
    expect($result)->toBe('{&quot;foo&quot;:&quot;bar&quot;,&quot;bool&quot;:true,&quot;int&quot;:2,&quot;float&quot;:1.2,&quot;numeric&quot;:2.5}');
});

test('returns an empty string if the value is empty', function () {
    expect(jsonAttr([]))->toBe('');
    expect(jsonAttr(null))->toBe('');
    expect(jsonAttr(false))->toBe('');
});
