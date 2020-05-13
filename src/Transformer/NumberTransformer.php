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

use Pitch\Liform\TransformResult;
use Symfony\Component\Form\FormView;

/**
 * @author Nacho Martín <nacho@limenius.com>
 * @author Philipp Fritsche <ph.fritsche@gmail.com>
 */
class NumberTransformer implements TransformerInterface
{
    public function transform(
        FormView $view
    ): TransformResult {
        $result = new TransformResult();

        if (\in_array('integer', $view->vars['block_prefixes'])) {
            $result->schema->type = 'integer';
        } else {
            $result->schema->type = 'number';
        }

        $result->schema->minimum = $view->vars['attr']['min'] ?? null;
        $result->schema->maximum = $view->vars['attr']['max'] ?? null;
        $result->schema->step = $view->vars['attr']['step'] ?? null;

        return $result;
    }
}
