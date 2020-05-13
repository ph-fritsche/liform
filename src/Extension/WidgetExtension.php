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
 * Copies the (reversed) block prefixes from FormViews to a widget property of JsonSchema.
 */
class WidgetExtension implements ExtensionInterface
{
    public function apply(
        TransformResult $transformResult,
        FormView $formView
    ) {
        if (!isset($transformResult->schema->widget)) {
            $transformResult->schema->widget = array_reverse($formView->vars['block_prefixes']);
        }
    }
}
