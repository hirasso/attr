<?php

\test('generates an attribute string', function () {
    $result = (string) \attr(['class' => 'border border-red bg-black']);
    \expect($result)->toBe(' class="border border-red bg-black" ');
});

\test('allows numeric key, string as value for "class"', function () {
    $result = (string) \attr(['class' => ['border border-red bg-black']]);
    \expect($result)->toBe(' class="border border-red bg-black" ');
});

\test('supports boolean attributes', function () {
    $result = (string) \attr([
        'data-current' => true,
    ]);
    \expect($result)->toBe(' data-current ');
});

\test('strips attributes that are exactly false or null', function () {
    $result = (string) \attr([
        'data-string-numeric' => '0',
        'data-string-empty' => '',
        'data-string-space' => ' ',
        'data-int-zero' => 0,
        'isFalse' => false,
        'isNull' => null,
        'class' => [
            'bg-red' => false,
        ],
    ]);
    \expect($result)->toBe(' data-string-numeric="0" data-string-empty="" data-string-space=" " data-int-zero="0" ');
});

\test('returns an empty string if all attributes are null or false', function () {
    $result = (string) \attr([
        'foo' => false,
        'class' => [
            'bg-red' => null,
        ],
        'style' => [
            'font-weight' => false,
        ],
    ]);
    \expect($result)->toBe('');
});

\test('handles strings for style and class', function () {
    $result = (string) \attr([
        'class' => 'border border-red',
        'style' => 'color: black;',
    ]);
    \expect($result)->toBe(' class="border border-red" style="color: black;" ');
});

\test('handles arrays for style and class', function () {
    $result = (string) \attr([
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
    \expect($result)->toBe(' class="border border-red" style="color: black; background: white" ');
});

\test('throws when provided with a list like attr(["foo", "bar", ...])', function () {
    // @phpstan-ignore argument.type
    \attr(['foo', 'bar'])->toString();
})->throws(InvalidArgumentException::class);

\test('throws when provided with a non-associative array for the "style" attribute', function () {
    \attr(['style' => ['foo', 'bar']])->toString();
})->throws(InvalidArgumentException::class);

\test('allows a non-associative array for the "class" attribute', function () {
    $result = (string) \attr(['class' => ['foo', 'bar']]);
    \expect($result)->toBe(' class="foo bar" ');
});

\test('throws when provided with a nested array for any attribute', function () {
    // @phpstan-ignore argument.type
    \attr([
        'class' => [
            'foo' => ['bar'],
        ],
    ])->toString();
})->throws(InvalidArgumentException::class);

\test('throws when provided with an array for any other attribute then "style" or "class"', function () {
    \attr(['foo' => ['foo' => 'bar']])->toString();
})->throws(InvalidArgumentException::class);

\test('escapes attributes', function () {
    $malicious = \getMaliciousAttributeValue();

    $result = (string) \attr(['value' => $malicious]);
    \expect($result)->toBe(' value="&quot; onload=&quot;alert(&#039;Hacked!&#039;)&quot;" ');

    $result = (string) \attr(['class' => [$malicious => true]]);
    \expect($result)->toBe(' class="&quot; onload=&quot;alert(&#039;Hacked!&#039;)&quot;" ');
});

\test('escapes style attributes', function () {
    $malicious = \getMaliciousAttributeValue();

    $result = (string) \attr(['style' => ['color' => $malicious]]);
    \expect($result)->toBe(' style="color: &quot; onload=&quot;alert(&#039;Hacked!&#039;)&quot;" ');

    $result = (string) \attr(['style' => [$malicious => 'red']]);
    \expect($result)->toBe(' style="&quot; onload=&quot;alert(&#039;Hacked!&#039;)&quot;: red" ');
});

\test('throws if provided with boolean true for nested style values', function () {
    \attr([
        'style' => [
            'background' => true,
        ],
    ])->toString();
})->throws(InvalidArgumentException::class);

\test('throws if provided with a string for nested class values', function () {
    \attr([
        'class' => [
            'bg-green' => 'yes',
        ],
    ])->toString();
})->throws(InvalidArgumentException::class);

\test('supports colons in keys and values', function () {
    $result = (string) \attr([
        'class' => 'hidden md:block',
        'x-data' => '{open: false}',
        ':class' => '{"bg-red": open}',
    ]);
    \expect($result)->toBe(' class="hidden md:block" x-data="{open: false}" :class="{&quot;bg-red&quot;: open}" ');
});

\test('supports floats as values', function () {
    $result = (string) \attr([
        'data-float' => 1.3,
    ]);
    \expect($result)->toBe(' data-float="1.3" ');
});

\test('does not double-encode values', function () {
    $result = (string) \attr([
        'value' => '&amp; &lt; &gt; &quot; &#039;',
    ]);
    \expect($result)->toBe(' value="&amp; &lt; &gt; &quot; &#039;" ');
});

\test('encodes single quotes', function () {
    $result = (string) \attr(['value' => "'"]);
    \expect($result)->toBe(' value="&#039;" ');
});

\test('encodes double quotes', function () {
    $result = (string) \attr(['value' => "\""]);
    \expect($result)->toBe(' value="&quot;" ');
});

\test('returns Builder for chaining', function () {
    $result = \attr(['type' => 'button']);
    \expect($result)->toBeInstanceOf(\Hirasso\Attr\Builder::class);
});

\test('supports chaining after array initialization', function () {
    $isActive = true;
    $result = (string) \attr(['type' => 'button'])
        ->class('btn')
        ->class('active', when: $isActive);

    \expect($result)->toBe(' type="button" class="btn active" ');
});
