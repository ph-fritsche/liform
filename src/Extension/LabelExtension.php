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
 * Extracts the label from FormViews and applies it to the title property of JsonSchema.
 */
class LabelExtension implements ExtensionInterface
{
    protected ?TranslatorInterface $translator;

    public function __construct(
        TranslatorInterface $translator = null
    ) {
        $this->translator = $translator;
    }

    public function apply(
        TransformResult $transformResult,
        FormView $formView)
    {
        if (isset($formView->vars['label'])) {
            $label = $formView->vars['label'];
        } elseif (isset($formView->vars['label_format'])) {
            $label = $this->getLabelByFormat($formView);
        } elseif (isset($formView->vars['name']) && !\is_numeric($formView->vars['name'])) {
            $label = $formView->vars['name'];
        } else {
            return;
        }

        // don't include placeholders as title
        if (substr($label, 0, 2) === '__' && substr($label, -2) === '__') {
            return;
        }

        if (isset($this->translator)) {
            $label = $this->translator->trans(
                $label,
                $formView->vars['label_translation_parameters'],
                $formView->vars['translation_domain'],
            );
        }

        $transformResult->schema->setTitle($label);
    }

    protected function getLabelByFormat(
        FormView $formView
    ): string {
        return strtr($formView->vars['label_format'], [
            '%id%' => $formView->vars['id'],
            '%name%' => $formView->vars['name'],
        ]);
    }
}
