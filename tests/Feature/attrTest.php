<?php

test('generates an attribute string', function () {
    $result = attr(['class' => 'border border-red bg-black']);
    expect($result)->toBe(' class="border border-red bg-black" ');
});

test('supports boolean attributes', function () {
    $result = attr([
        'data-current' => true,
    ]);
    expect($result)->toBe(' data-current ');
});

test('strips attributes that are exactly false or null', function () {
    $result = attr([
        'tabindex' => '0',
        'data-value' => 0,
        'isFalse' => false,
        'isNull' => null,
        'class' => [
            'bg-red' => false,
        ],
    ]);
    expect($result)->toBe(' tabindex="0" data-value="0" ');
});

test('returns an empty string if all attributes are null or false', function () {
    $result = attr([
        'foo' => false,
        'class' => [
            'bg-red' => null,
        ],
        'style' => [
            'font-weight' => false,
        ],
    ]);
    expect($result)->toBe('');
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
    expect($result)->toBe(' class="border border-red" style="color: black; background: white" ');
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

test('escapes attributes', function () {
    $malicious = getMaliciousAttributeValue();

    $result = attr(['value' => $malicious]);
    expect($result)->toBe(' value="&quot; onload=&quot;alert(&#039;Hacked!&#039;)&quot;" ');

    $result = attr(['class' => [$malicious => true]]);
    expect($result)->toBe(' class="&quot; onload=&quot;alert(&#039;Hacked!&#039;)&quot;" ');
});

test('escapes style attributes', function () {
    $malicious = getMaliciousAttributeValue();

    $result = attr(['style' => ['color' => $malicious]]);
    expect($result)->toBe(' style="color: &quot; onload=&quot;alert(&#039;Hacked!&#039;)&quot;" ');

    $result = attr(['style' => [$malicious => 'red']]);
    expect($result)->toBe(' style="&quot; onload=&quot;alert(&#039;Hacked!&#039;)&quot;: red" ');
});

test('throws if provided with boolean true for nested style values', function () {
    expect(attr([
        'style' => [
            'background' => true,
        ],
    ]));
})->throws(InvalidArgumentException::class);

test('throws if provided with a string for nested class values', function () {
    expect(attr([
        'class' => [
            'bg-green' => 'yes',
        ],
    ]));
})->throws(InvalidArgumentException::class);

test('supports colons in keys and values', function () {
    $result = attr([
        'class' => 'hidden md:block',
        'x-data' => '{open: false}',
        ':class' => '{"bg-red": open}',
    ]);
    dump($result);
    expect($result)->toBe(' class="hidden md:block" x-data="{open: false}" :class="{&quot;bg-red&quot;: open}" ');
});
