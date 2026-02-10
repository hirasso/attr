---
"attr": minor
---

Allow numerical keys for 'class' arrays:

```php
attr([
  'class' => [
    'foo bar', // <-- now supported
    'baz' => true,
    'qux' => false
  ]
])
```
