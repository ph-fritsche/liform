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
use Pitch\Liform\Result\Meta;
use Swaggest\JsonSchema\Schema;

class TransformResult
{
    /**
     * Schema describing what data should be supplied by and to the Form.
     * This should also contain every information necessary to render adequate UI.
     */
    public Schema $schema;

    /**
     * Attributes to the value like errors, notifications, etc.
     */
    public Meta $meta;

    /**
     * If initialized, should contain the view data.
     */
    public $value;

    public function __construct(
        Schema $schema = null,
        Meta $meta = null
    ) {
        $this->schema = $schema ?? new Schema();
        $this->meta = $meta ?? new Meta();
        unset($this->value);
    }

    public function hasValue(): bool
    {
        $valueProp = new ReflectionProperty(static::class, 'value');
        return $valueProp->isInitialized($this);
    }
}
