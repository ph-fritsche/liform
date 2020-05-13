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

use Pitch\Liform\LiformInterface;
use Pitch\Liform\TransformResult;
use Symfony\Component\Form\FormView;

/**
 * @author Nacho Martín <nacho@limenius.com>
 * @author Philipp Fritsche <ph.fritsche@gmail.com>
 */
class CompoundTransformer implements TransformerInterface
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

        if ($view->vars['compound'] ?? false) {
            $result->schema->type = 'object';
            $result->value = [];

            $i = 0;
            foreach ($view as $id => $child) {
                $childResult = $this->liform->transform($child);

                if ($child->vars['required'] ?? false) {
                    $result->schema->required[] = $id;
                }

                $childResult->schema->propertyOrder = $i++;

                $result->schema->setProperty($id, $childResult->schema);
                if ($result->hasValue()) {
                    $result->value[$id] = $childResult->value;
                }
            }
        }

        return $result;
    }
}
