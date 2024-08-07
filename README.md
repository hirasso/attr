# attr

A tiny php attribute helper

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

## `jsonInAttr()`

Render JSON so that it is safe to be used inside an HTMLElement attribute:

```php
/** Example: render an attribute to be used by Alpine.js */
echo attr([
  'x-data' => jsonInAttr([
      'open' => 'true',
  ])
])
```