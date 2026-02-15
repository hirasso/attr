<?php

/*
 * Copyright (c) Rasso Hilber
 * https://rassohilber.com
 */

declare(strict_types=1);

namespace Hirasso\Attr;

use Stringable;

/**
 * Fluent builder for HTML attributes
 */
final class Builder implements Stringable
{
    /** @var array<string, mixed> Raw attributes from merge() */
    private array $rawAttributes = [];

    /** @var array<string, string|int|float|bool|null> Fluent-added attributes */
    private array $attributes = [];

    /** @var array<string, bool> Fluent-added classes */
    private array $classes = [];

    /** @var array<string, string|int|float> Fluent-added styles */
    private array $styles = [];

    public static function make(): self
    {
        return new self();
    }

    /**
     * Set any attribute
     *
     * ->set('type', 'button')
     * ->set('disabled', true)
     * ->set('data-id', $id)
     * ->set('aria-hidden', $condition ? "true" : null)
     */
    public function set(
        string $name,
        string|int|float|bool|null $value,
    ): self {
        if ($value !== null && $value !== false) {
            $this->attributes[$name] = $value;
        }

        return $this;
    }

    /**
     * Short cut for aria attributes
     *
     * @param non-empty-string $name
     * @param non-empty-string $value
     */
    public function aria(string $name, string $value, ?bool $when = true): self
    {
        if ($when && \trim($value) !== '') {
            $this->attributes["aria-{$name}"] = $value;
        }

        return $this;
    }

    /**
     * Add class(es)
     *
     * ->class('border p-3')
     * ->class('hidden', $isHidden)
     * ->class('active', $isActive)
     */
    public function class(string $class, ?bool $when = true): self
    {
        if ($when) {
            $parts = \preg_split('/\s+/', $class, -1, PREG_SPLIT_NO_EMPTY);
            if ($parts !== false) {
                foreach ($parts as $c) {
                    $this->classes[$c] = true;
                }
            }
        }

        return $this;
    }

    /**
     * Add style property
     *
     * ->style('color', 'red')
     * ->style('display', 'none', when: $isHidden)
     * ->style('--custom-var', $value)
     */
    public function style(
        string $property,
        string|int|float|null|false $value,
    ): self {
        if ($value !== null && $value !== false) {
            $this->styles[$property] = $value;
        }

        return $this;
    }

    /**
     * Merge in an array of attributes (for interop with existing code)
     *
     * Stores raw attributes to be validated by Attr::attr() on output.
     *
     * @param array<string, mixed> $attributes
     */
    public function merge(array $attributes): self
    {
        foreach ($attributes as $key => $value) {
            $this->rawAttributes[$key] = $value;
        }

        return $this;
    }

    /**
     * Build the final attributes array
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $attrs = $this->rawAttributes;

        foreach ($this->attributes as $key => $value) {
            $attrs[$key] = $value;
        }

        $attrs = $this->mergeClasses($attrs);
        return $this->mergeStyles($attrs);
    }

    /**
     * @param  array<string, mixed>  $attrs
     * @return array<string, mixed>
     */
    private function mergeClasses(array $attrs): array
    {
        if ($this->classes === []) {
            return $attrs;
        }

        $existingClass = $attrs['class'] ?? null;
        $fluentClasses = \implode(' ', \array_keys($this->classes));

        if ($existingClass === null) {
            $attrs['class'] = $fluentClasses;

            return $attrs;
        }

        if (\is_string($existingClass)) {
            $attrs['class'] = \trim($existingClass.' '.$fluentClasses);

            return $attrs;
        }

        if (\is_array($existingClass)) {
            foreach ($this->classes as $class => $condition) {
                $existingClass[$class] = $condition;
            }
            $attrs['class'] = $existingClass;
        }

        return $attrs;
    }

    /**
     * @param  array<string, mixed>  $attrs
     * @return array<string, mixed>
     */
    private function mergeStyles(array $attrs): array
    {
        if ($this->styles === []) {
            return $attrs;
        }

        $existingStyle = $attrs['style'] ?? null;

        if ($existingStyle === null) {
            $attrs['style'] = $this->styles;

            return $attrs;
        }

        if (\is_string($existingStyle)) {
            $parts = [];
            foreach ($this->styles as $prop => $val) {
                $parts[] = "{$prop}: {$val}";
            }
            $attrs['style'] = \rtrim($existingStyle, '; ').'; '.\implode('; ', $parts);

            return $attrs;
        }

        if (\is_array($existingStyle)) {
            foreach ($this->styles as $prop => $val) {
                $existingStyle[$prop] = $val;
            }
            $attrs['style'] = $existingStyle;
        }

        return $attrs;
    }

    /**
     * Render as HTML attribute string
     */
    public function toString(): string
    {
        return Attr::attr($this->toArray());
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
