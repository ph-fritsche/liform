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
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\ChoiceList\View\ChoiceListView;
use Symfony\Component\Form\ChoiceList\View\ChoiceGroupView;
use Symfony\Contracts\Translation\TranslatorInterface;
use Swaggest\JsonSchema\Schema;

/**
 * @author Nacho Martín <nacho@limenius.com>
 * @author Philipp Fritsche <ph.fritsche@gmail.com>
 */
class ChoiceTransformer implements TransformerInterface
{
    private TranslatorInterface $translator;

    public function __construct(
        TranslatorInterface $translator
    ) {
        $this->translator = $translator;
    }

    public function transform(
        FormView $view
    ): TransformResult {
        $result = new TransformResult();

        $choices = [];
        $choicesTitles = [];
        foreach ($this->iterateChoices($view->vars['choices']) as $choiceView) {
            $choices[] = $choiceView->value;
            $choicesTitles[] = $this->translator->trans($choiceView->label, [], $view->vars['choice_translation_domain'] ?? null);
        }

        if ($view->vars['multiple'] ?? false) {

            $result->schema->type = 'array';

            $result->schema->items = new Schema();
            $result->schema->items->type = 'string';

            $result->schema->items->enum = $choices;
            $result->schema->items->enumTitles = $choicesTitles;

            $result->schema->minItems = $view->vars['required'] ?? false ? 1 : 0;
            $result->schema->uniqueItems = true;

        } else {

            $result->schema->type = 'string';

            // If empty, the enum property throws validation errors
            if (count($choices) > 0) {
                $result->schema->enum = $choices;
                $result->schema->enumTitles = $choicesTitles;
            }
        }

        if ($view->vars['expanded']) {
            $result->schema->choiceExpanded = true;
        }
            
        return $result;
    }

    /**
     * @param ChoiceView|ChoiceListView|ChoiceGroupView|iterable $choices
     */
    private function iterateChoices(
        $choices
    ) {
        if ($choices instanceof ChoiceView) {
            yield $choices;
        } elseif ($choices instanceof ChoiceListView) {
            foreach ($choices->preferredChoices as $choice) {
                yield from $this->iterateChoices($choice);
            }
            foreach ($choices->choices as $choice) {
                yield from $this->iterateChoices($choice);
            }
        } elseif (is_iterable($choices)) {
            foreach ($choices as $choice) {
                yield from $this->iterateChoices($choice);
            }
        }
    }
}
