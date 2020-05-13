<?php

/*
 * Original file is part of the Limenius\Liform package.
 *
 * (c) Limenius <https://github.com/Limenius/>
 *
 * This file is part of the Pitch\Liform package.
 *
 * (c) Philipp Fritsche <ph.fritsche@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pitch\Liform\Transformer;

use Pitch\Liform\Exception\TransformerException;
use Pitch\Liform\LiformInterface;
use Pitch\Liform\TransformResult;
use Symfony\Component\Form\FormView;

/**
 * @author Nacho Martín <nacho@limenius.com>
 * @author Philipp Fritsche <ph.fritsche@gmail.com>
 */
class ArrayTransformer implements TransformerInterface
{
    protected LiformInterface $liform;

    public function __construct(
        LiformInterface $liform
    ) {
        $this->liform = $liform;
    }

    public function transform(
        FormView $view
    ): TransformResult {
        $result = new TransformResult();

        $result->schema->type = 'array';
        $result->value = [];

        $prototype = isset($view->vars['prototype']) ? $this->liform->transform($view->vars['prototype']) : null;

        $childSchemas = [];
        $childSchemasSame = true;

        foreach ($view as $i => $child) {
            $childResult = $this->liform->transform($child);

            $result->value[] = $childResult->value ?? null;
            $childSchemas[] = $childResult->schema;

            if ($i > 0 && $childResult->schema != $childSchemas[0]) {
                $childSchemasSame = false;
            }
        }

        if (isset($childSchemas[0])) {
            $result->schema->items = $childSchemasSame ? $childSchemas[0] : $childSchemas;
            if ($prototype && (!$childSchemasSame || $prototype->schema != $childSchemas[0])) {
                $result->schema->additionalItems = $prototype->schema;
            }
        } elseif($prototype) {
            $result->schema->items = $prototype->schema;
        }

        if ($prototype) {
            $result->schema->prototypeValue = $prototype->value;
        }

        $result->schema->allowAdd = $view->vars['allow_add'];
        $result->schema->allowDelete = $view->vars['allow_delete'];

        return $result;
    }
}
