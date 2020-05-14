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
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\FormView;

/**
 * Extracts the errors from the FormView.
 */
class ErrorExtension implements ExtensionInterface
{
    public function apply(
        TransformResult $transformResult,
        FormView $formView
    ) {
        if (!isset($formView->vars['errors']) || !$formView->vars['errors'] instanceof FormErrorIterator) {
            return;
        }

        foreach ($formView->vars['errors'] as $error) {
            $transformResult->meta['errors'][] = $error->getMessage();
        }
    }
}
