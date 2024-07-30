# attr

A tiny php attribute helper

## Installation

```shell
composer require hirasso/attr
```

## Usage

You define your attributes in an associative way:

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
