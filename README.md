# hirasso/attr

[![Latest Version on Packagist](https://img.shields.io/packagist/v/hirasso/attr.svg)](https://packagist.org/packages/hirasso/attr)
[![Test Status](https://img.shields.io/github/actions/workflow/status/hirasso/attr/ci.yml?label=tests)](https://github.com/hirasso/attr/actions/workflows/ci.yml)

**A tiny HTML attribute generator written in PHP. Great for projects using tailwindcss and Alpine.js 🎡**

## Installation

```shell
composer require hirasso/attr
```

## Usage

### `attr()`

Define your attributes in an associative way:

```php
/** Example: render a button with custom classes and styles */
<button <?= attr([
            'type' => 'button',
            'class' => [
                'border border-current p-3' => true,
                'bg-white text-black' => !$isActive,
                'bg-blue-600 text-white' => $isActive
            ],
            'style' => [
                '--active-color' => 'red'
            ],
            'data-toggle' => true
        ]) ?>>
    Click Me!
</button>
```

...and the `attr` function transforms them into normal HTML:

```html
<button type="button" class="border border-current p-3 bg-white text-black" style="--active-color: red;" data-toggle>
  Click Me!
</button>
```

## `jsonAttr()`

Render JSON so that it is safe to be used inside an HTMLElement attribute:

```php
/** Example: render an x-data attribute for Alpine.js */
<div <?= attr([
  'x-data' => jsonAttr([
      'open' => true,
      "message" => "This 'quote' contains <b>bold text</b>"
  ])
]) ?>>
</div>
```

..the output will look like this and can be consumed by JavaScript:

```html
<div x-data="{&quot;open&quot;:true,&quot;message&quot;:&quot;This &#039;quote&#039; contains &lt;b&gt;bold text&lt;\/b&gt;&quot;}"></div>
```

More examples can be seen by browsing the [test](./tests/Feature/attrTest.php) files.