# Changelog

## 2.0.0

### Major Changes

- 4f68dd6: New dependency introduced: `illuminate/collections`. This has the benefit of making
  validation and transformation of nested arrays much more convenient.
- 4f68dd6: Only the `style` and `class` keys now support arrays as values. All others must have primitives as values.

### Patch Changes

- 4f68dd6: Add phpstan analysis level 5
- 4f68dd6: Add tests for the `attr()` and `jsonAttr()` functions
