<?php

use Hirasso\Attr\AttrBuilder;

\test('creates empty attributes', function () {
    $result = AttrBuilder::make()->toString();
    \expect($result)->toBe('');
});

\test('sets basic attributes', function () {
    $result = AttrBuilder::make()
        ->set('type', 'button')
        ->set('id', 'my-btn')
        ->toString();

    \expect($result)->toBe(' type="button" id="my-btn" ');
});

\test('sets boolean attributes', function () {
    $result = AttrBuilder::make()
        ->set('disabled', true)
        ->set('readonly', true)
        ->toString();

    \expect($result)->toBe(' disabled readonly ');
});

\test('set ignores null and false values', function () {
    $result = AttrBuilder::make()
        ->set('data-active', 'yes')
        ->set('data-null', null)
        ->set('data-false', false)
        ->toString();

    \expect($result)->toBe(' data-active="yes" ');
});

\test('adds single class', function () {
    $result = AttrBuilder::make()
        ->class('border')
        ->toString();

    \expect($result)->toBe(' class="border" ');
});

\test('adds multiple classes in one call', function () {
    $result = AttrBuilder::make()
        ->class('border p-3 rounded')
        ->toString();

    \expect($result)->toBe(' class="border p-3 rounded" ');
});

\test('chains multiple class calls', function () {
    $result = AttrBuilder::make()
        ->class('border')
        ->class('p-3')
        ->class('rounded')
        ->toString();

    \expect($result)->toBe(' class="border p-3 rounded" ');
});

\test('conditional class with when parameter', function () {
    $isActive = true;
    $isHidden = false;

    $result = AttrBuilder::make()
        ->class('base')
        ->class('active', when: $isActive)
        ->class('hidden', when: $isHidden)
        ->toString();

    \expect($result)->toBe(' class="base active" ');
});

\test('deduplicates classes', function () {
    $result = AttrBuilder::make()
        ->class('border')
        ->class('border p-3')
        ->class('border')
        ->toString();

    \expect($result)->toBe(' class="border p-3" ');
});

\test('adds single style', function () {
    $result = AttrBuilder::make()
        ->style('color', 'red')
        ->toString();

    \expect($result)->toBe(' style="color: red" ');
});

\test('chains multiple style calls', function () {
    $result = AttrBuilder::make()
        ->style('color', 'red')
        ->style('background', 'white')
        ->toString();

    \expect($result)->toBe(' style="color: red; background: white" ');
});

\test('conditional style with when parameter', function () {
    $result = AttrBuilder::make()
        ->style('color', 'red')
        ->style('background', false)
        ->toString();

    \expect($result)->toBe(' style="color: red" ');
});

\test('style ignores null values', function () {
    $color = null;

    $result = AttrBuilder::make()
        ->style('color', $color)
        ->style('background', 'white')
        ->toString();

    \expect($result)->toBe(' style="background: white" ');
});

\test('supports CSS custom properties', function () {
    $result = AttrBuilder::make()
        ->style('--primary-color', '#ff0000')
        ->style('--spacing', '1rem')
        ->toString();

    \expect($result)->toBe(' style="--primary-color: #ff0000; --spacing: 1rem" ');
});

\test('supports numeric style values', function () {
    $result = AttrBuilder::make()
        ->style('opacity', 0.5)
        ->style('z-index', 100)
        ->toString();

    \expect($result)->toBe(' style="opacity: 0.5; z-index: 100" ');
});

\test('data attributes via set', function () {
    $result = AttrBuilder::make()
        ->set('data-id', 123)
        ->set('data-active', true)
        ->set('data-name', 'test')
        ->toString();

    \expect($result)->toBe(' data-id="123" data-active data-name="test" ');
});

\test('aria helper adds aria- prefix', function () {
    $result = AttrBuilder::make()
        ->aria('label', 'Close button')
        ->aria('hidden', 'true')
        ->toString();

    \expect($result)->toBe(' aria-label="Close button" aria-hidden="true" ');
});

\test('aria with conditional when parameter', function () {
    $result = AttrBuilder::make()
        ->aria('expanded', 'true', when: true)
        ->aria('disabled', 'true', when: false)
        ->toString();

    \expect($result)->toBe(' aria-expanded="true" ');
});

\test('aria ignores empty values', function () {
    $result = AttrBuilder::make()
        // @phpstan-ignore argument.type
        ->aria('label', '')
        ->aria('description', '  ')
        ->aria('hidden', 'true')
        ->toString();

    \expect($result)->toBe(' aria-hidden="true" ');
});

\test('combines all attribute types', function () {
    $result = AttrBuilder::make()
        ->set('type', 'button')
        ->class('btn btn-primary')
        ->class('disabled', when: true)
        ->style('color', 'white')
        ->set('data-action', 'submit')
        ->aria('label', 'Submit form')
        ->toString();

    \expect($result)->toBe(' type="button" data-action="submit" aria-label="Submit form" class="btn btn-primary disabled" style="color: white" ');
});

\test('merge accepts array of attributes', function () {
    $result = AttrBuilder::make()
        ->merge([
            'type' => 'button',
            'disabled' => true,
        ])
        ->toString();

    \expect($result)->toBe(' type="button" disabled ');
});

\test('merge handles class array', function () {
    $result = AttrBuilder::make()
        ->merge([
            'class' => [
                'border' => true,
                'hidden' => false,
                'active' => true,
            ],
        ])
        ->toString();

    \expect($result)->toBe(' class="border active" ');
});

\test('merge handles class string', function () {
    $result = AttrBuilder::make()
        ->merge([
            'class' => 'border p-3',
        ])
        ->toString();

    \expect($result)->toBe(' class="border p-3" ');
});

\test('merge handles style array', function () {
    $result = AttrBuilder::make()
        ->merge([
            'style' => [
                'color' => 'red',
                'background' => null,
                'border' => false,
            ],
        ])
        ->toString();

    \expect($result)->toBe(' style="color: red" ');
});

\test('toArray returns attributes array', function () {
    $result = AttrBuilder::make()
        ->set('type', 'button')
        ->class('btn')
        ->style('color', 'red')
        ->toArray();

    \expect($result)->toBe([
        'type' => 'button',
        'class' => 'btn',
        'style' => ['color' => 'red'],
    ]);
});

\test('implements Stringable', function () {
    $builder = AttrBuilder::make()->set('type', 'button');

    \expect((string) $builder)->toBe(' type="button" ');
});

\test('attr() helper returns AttrBuilder', function () {
    $result = \attr()
        ->set('type', 'button')
        ->class('btn')
        ->toString();

    \expect($result)->toBe(' type="button" class="btn" ');
});

\test('escapes attribute values', function () {
    $malicious = \getMaliciousAttributeValue();

    $result = AttrBuilder::make()
        ->set('value', $malicious)
        ->toString();

    \expect($result)->toBe(' value="&quot; onload=&quot;alert(&#039;Hacked!&#039;)&quot;" ');
});

\test('escapes class names', function () {
    $malicious = \getMaliciousAttributeValue();

    $result = AttrBuilder::make()
        ->class($malicious)
        ->toString();

    \expect($result)->toBe(' class="&quot; onload=&quot;alert(&#039;Hacked!&#039;)&quot;" ');
});

\test('escapes style values', function () {
    $malicious = \getMaliciousAttributeValue();

    $result = AttrBuilder::make()
        ->style('color', $malicious)
        ->toString();

    \expect($result)->toBe(' style="color: &quot; onload=&quot;alert(&#039;Hacked!&#039;)&quot;" ');
});

\test('real-world example: button with conditional states', function () {
    $isLoading = true;
    $isDisabled = false;
    $variant = 'primary';

    $result = AttrBuilder::make()
        ->set('type', 'submit')
        // @phpstan-ignore booleanOr.leftAlwaysTrue, booleanOr.rightAlwaysFalse
        ->set('disabled', $isLoading || $isDisabled)
        ->class('btn px-4 py-2 rounded')
        // @phpstan-ignore identical.alwaysTrue
        ->class('btn-primary', when: $variant === 'primary')
        // @phpstan-ignore identical.alwaysFalse
        ->class('btn-secondary', when: $variant === 'secondary')
        ->class('opacity-50 cursor-wait', when: $isLoading)
        ->class('cursor-not-allowed', when: $isDisabled)
        ->set('data-loading', $isLoading)
        ->aria('busy', 'true', when: $isLoading)
        ->aria('hidden', 'true', when: $isDisabled)
        ->style('--color', 'red')
        ->style('border', false)
        ->style('border', null)
        ->toString();

    \expect($result)->toContain('type="submit"');
    \expect($result)->toContain('disabled');
    \expect($result)->toContain('btn-primary');
    \expect($result)->toContain('opacity-50');
    \expect($result)->toContain('cursor-wait');
    \expect($result)->toContain('data-loading');
    \expect($result)->toContain('aria-busy="true"');
    \expect($result)->toContain('--color: red');
    \expect($result)->not->toContain('aria-hidden');
    \expect($result)->not->toContain('border:');
    \expect($result)->not->toContain('btn-secondary');
    \expect($result)->not->toContain('cursor-not-allowed');
});
