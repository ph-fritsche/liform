<?php

/*
 * This file is part of the Pitch\Liform package.
 *
 * (c) Philipp Fritsche <ph.fritsche@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pitch\Liform;

use ReflectionProperty;
use Swaggest\JsonSchema\Schema;

class TransformResult
{
    public Schema $schema;

    public $value;

    public function __construct(
        Schema $schema = null
    ) {
        $this->schema = $schema ?? new Schema();
    }

    public function hasValue(): bool
    {
        $valueProp = new ReflectionProperty(static::class, 'value');
        return $valueProp->isInitialized($this);
    }
}
