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
 * Extracts the disabled flag from FormViews and applies it to a disabled property of JsonSchema.
 */
class DisabledExtension implements ExtensionInterface
{
    public function apply(
        TransformResult $transformResult,
        FormView $formView
    ) {
        if ($formView->vars['disabled'] ?? false) {
            $transformResult->schema->disabled = true;
        }
    }
}
