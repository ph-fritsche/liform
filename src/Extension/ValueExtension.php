<?php

/*
 * This file is part of the Pitch\Liform package.
 *
 * (c) Philipp Fritsche <ph.fritsche@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pitch\Liform\Extension;

use Pitch\Liform\TransformResult;
use Symfony\Component\Form\FormView;

/**
 * Extracts the value from non-compound FormViews.
 */
class ValueExtension implements ExtensionInterface
{
    public function apply(
        TransformResult $transformResult,
        FormView $formView
    ) {
        if ($formView->vars['compound'] ?? false) {
            return;
        }

        if (!empty($formView->vars['value'])) {
            $transformResult->value = $formView->vars['value'];

            if ($transformResult->schema->type === 'boolean') {
                $transformResult->value = (bool) $transformResult->value;
            } elseif ($transformResult->schema->type === 'integer') {
                $transformResult->value = (int) $transformResult->value;
            } elseif ($transformResult->schema->type === 'number') {
                $transformResult->value = (float) $transformResult->value;
            }
        }
    }
}
