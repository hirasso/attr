# Changelog

## 4.1.0

### Minor Changes

- cb5cf62: New fluent API via `Attr\Builder`:

  ```php
  <button <?= attr()
      ->set('type', 'button')
      ->class('border p-3')
      ->class('bg-blue-600', $isActive)
      ->style('--highlight', 'red')
      ->aria('expanded', 'false') ?>>
  ```

## 4.0.0

### Major Changes

- 078da1c: Do not convert numeric strings to `int|float` in `jsonAttr()`
- 078da1c: Do not double-encode JSON – instead, use native `json_encode` flags and `htmlspecialchars`
- 078da1c: Always return strings from the `attr()` and `jsonAttr()` functions
- a06033d: Throw if `jsonAttr()` encounters an error

### Patch Changes

- 7cb9a44: Add tests for double-encoding prevention
- a06033d: Where possible, prefer named over numeric entities in `jsonAttr()`
- e4e2589: Use scoped `collect` function for `illuminate/collections`

## 3.0.2

### Patch Changes

- e17df2d: Allow illuminate/collections^12

## 3.0.1

### Patch Changes

- cc69df3: Allow `float` as type for attribute values

## 3.0.0

### Major Changes

- c6bab48: BREAKING: Do not strip empty string values anymore. Also, keep strings basically untouched. Only escape them.

## 2.0.3

### Patch Changes

- 45c69b6: Relax illuminate/collections version constraint to `^11`

## 2.0.2

### Patch Changes

- 161ead8: Automatically format the source code at `pre-commit` via husky+lint-staged
- b8e0b1a: 100% code coverage
- 81f458f: Upload code coverage to codecov and add a coverage badge to the readme
- 4160ce6: refactor

## 2.0.1

### Patch Changes

- 1b5fcff: Optimize examples in readme file

## 2.0.0

### Major Changes

- 4f68dd6: New dependency introduced: `illuminate/collections`. This has the benefit of making
  validation and transformation of nested arrays much more convenient.
- 4f68dd6: Only the `style` and `class` keys now support arrays as values. All others must have primitives as values.

### Patch Changes

- 4f68dd6: Add phpstan analysis level 5
- 4f68dd6: Add tests for the `attr()` and `jsonAttr()` functions
