<?php

declare(strict_types=1);

\test('generates a valid json attribute from an array', function () {
    $result = \jsonAttr([
        'foo' => 'bar',
        'bool' => true,
        'int' => 2,
        'float' => 1.2,
        'numeric' => '2.5',
        'xss' => '<script>alert("test")</script>',
        'amp' => 'a & b',
        'apos' => "it's",
    ]);
    \expect($result)->toBe(
        '{&quot;foo&quot;:&quot;bar&quot;,&quot;bool&quot;:true,&quot;int&quot;:2,&quot;float&quot;:1.2,&quot;numeric&quot;:&quot;2.5&quot;,&quot;xss&quot;:&quot;&lt;script&gt;alert(\&quot;test\&quot;)&lt;/script&gt;&quot;,&quot;amp&quot;:&quot;a &amp; b&quot;,&quot;apos&quot;:&quot;it&apos;s&quot;}',
    );
});

\test('generates a valid json attribute from an object', function () {
    $result = \jsonAttr((object) [
        'foo' => 'bar',
        'bool' => true,
        'int' => 2,
        'float' => 1.2,
        'numeric' => '2.5',
        'xss' => '<script>alert("test")</script>',
        'amp' => 'a & b',
        'apos' => "it's",
    ]);
    \expect($result)->toBe(
        '{&quot;foo&quot;:&quot;bar&quot;,&quot;bool&quot;:true,&quot;int&quot;:2,&quot;float&quot;:1.2,&quot;numeric&quot;:&quot;2.5&quot;,&quot;xss&quot;:&quot;&lt;script&gt;alert(\&quot;test\&quot;)&lt;/script&gt;&quot;,&quot;amp&quot;:&quot;a &amp; b&quot;,&quot;apos&quot;:&quot;it&apos;s&quot;}',
    );
});

\test('returns an empty string if the value is empty', function () {
    \expect(\jsonAttr([]))->toBeEmpty();
    \expect(\jsonAttr(null))->toBeEmpty();
    \expect(\jsonAttr(false))->toBeEmpty();
});

\test('does not double-encode values', function () {
    $result = \jsonAttr([
        'encoded' => '&amp; &lt; &gt; &quot; &#039;',
    ]);
    \expect($result)->toBe('{&quot;encoded&quot;:&quot;&amp; &lt; &gt; &quot; &#039;&quot;}');
});
