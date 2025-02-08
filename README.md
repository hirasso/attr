# attr

A tiny HTML attribute generator written in PHP. Great for projects using tailwindcss and Alpine.js 🎡

## Installation

```shell
composer require hirasso/attr
```

## Usage

### `attr()`

Define your attributes in an associative way:

```php
<button <?= attr([
            'type' => 'button',
            'class' => [
                'button button--primary' => true,
                'button--active' => $is_active
            ],
            'style' => [
                '--color' => 'red'
            ],
            'data-toggle' => true
        ]) ?>>
    Click Me!
</button>
```

...and the `attr` function transforms them into normal HTML:

```html
<button
  type="button"
  class="button button--primary button--active"
  style="--color: red;"
  data-toggle
>
  Click Me!
</button>
```

## `jsonAttr()`

Render JSON so that it is safe to be used inside an HTMLElement attribute:

```php
/** Example: render an attribute to be used by Alpine.js */
<div <?= attr([
  'x-data' => jsonAttr([
      'open' => true,
      "message" => "This 'quote' is <b>bold</b>"
  ])
]) ?>>
</div>
```

..the output will look like this and can be consumed by JavaScript:

```html
<div x-data="{&quot;open&quot;:true,&quot;message&quot;:&quot;This &#039;quote&#039; is &lt;b&gt;bold&lt;\/b&gt;&quot;}"></div>
```