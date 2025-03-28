# Changelog

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
