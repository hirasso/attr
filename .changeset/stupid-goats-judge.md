---
"attr": minor
---

New fluent API via `Attr\Builder`:

```php
<button <?= attr()
    ->set('type', 'button')
    ->class('border p-3')
    ->class('bg-blue-600', $isActive)
    ->style('--highlight', 'red')
    ->aria('expanded', 'false') ?>>
```
