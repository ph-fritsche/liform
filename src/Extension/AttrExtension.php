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
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Extracts the attr from FormViews.
 */
class AttrExtension implements ExtensionInterface
{
    protected ?TranslatorInterface $translator;

    public function __construct(
        TranslatorInterface $translator = null
    ) {
        $this->translator = $translator;
    }

    public function apply(
        TransformResult $transformResult,
        FormView $formView
    ) {
        $transformResult->schema->attr = $formView->vars['attr'];

        $placeholder = $formView->vars['attr']['placeholder'] ?? null;
        if (isset($placeholder)) {
            if (isset($this->translator)) {
                $placeholder = $this->translator->trans(
                    $placeholder,
                    $formView->vars['attr_translation_parameters'],
                    $formView->vars['translation_domain'],
                );
            }
            $transformResult->schema->placeholder = $placeholder;
        }
    }
}
