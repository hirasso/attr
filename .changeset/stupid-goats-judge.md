---
"attr": minor
---

New fluent API via `AttrBuilder`:

```php
<button <?= attr()
    ->set('type', 'button')
    ->class('border p-3')
    ->class('bg-blue-600', $isActive)
    ->style('--highlight', 'red')
    ->aria('expanded', 'false') ?>>
```
