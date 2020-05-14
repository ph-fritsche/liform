<?php

/*
 * This file is part of the Pitch\Liform package.
 *
 * (c) Philipp Fritsche <ph.fritsche@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pitch\Liform\Result;

use ArrayAccess;
use JsonSerializable;

/**
 * Holder for meta data that will be flattened when serialized to JSON.
 */
class Meta implements ArrayAccess, JsonSerializable
{
    protected array $data = [];

    /**
     * @var self[]
     */
    protected $properties = [];

    public function jsonSerialize(): ?array
    {
        $return = null;

        foreach ($this->iterateMeta($this) as $i => $m) {
            $return[$i][$m[0]] = $m[1];
        }

        return $return;
    }

    public function offsetExists($offset)
    {
        return \array_key_exists($offset, $this->data);
    }

    public function &offsetGet($offset)
    {
        return $this->data[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->data[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    public function hasPropertyMeta(
        string $propertyKey
    ): bool {
        return isset($this->properties[$propertyKey]);
    }

    public function getPropertyMeta(
        string $propertyKey
    ): Meta {
        return $this->properties[$propertyKey];
    }

    public function setPropertyMeta(
        string $propertyKey,
        Meta $meta
    ): void {
        $this->properties[$propertyKey] = $meta;
    }

    protected function iterateMeta(
        Meta $meta,
        string $path = null
    ) {
        foreach ($meta->data as $key => $value) {
            yield $key => [$path, $value];
        }

        foreach ($meta->properties as $propertyKey => $propertyMeta) {
            yield from $this->iterateMeta($propertyMeta, ($path ? $path . '.' : '') . $propertyKey);
        }
    }
}
