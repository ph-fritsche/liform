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
 * Extracts the help from FormViews and applies it to the description property of JsonSchema.
 */
class HelpExtension implements ExtensionInterface
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
        /** @var string */
        $help = $formView->vars['help'] ?? null;

        if (!isset($help)) {
            return;
        }

        if (isset($this->translator)) {
            $help = $this->translator->trans(
                $help,
                $formView->vars['help_translation_parameters'],
                $formView->vars['translation_domain'],
            );
        }

        $transformResult->schema->setDescription($help);
    }
}
