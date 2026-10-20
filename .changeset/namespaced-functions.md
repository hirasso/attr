---
"attr": minor
---

Expose `attr()` and `jsonAttr()` as namespaced functions `Hirasso\Attr\attr()` and `Hirasso\Attr\jsonAttr()`. They are always available, even if another package already declares a global `attr()`:

```php
use function Hirasso\Attr\attr;

attr()->class('foo');
```

The global helpers keep working as before and now delegate to the namespaced functions.
