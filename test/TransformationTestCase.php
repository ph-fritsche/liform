<?php

/*
 * This file is part of the Pitch\Liform package.
 *
 * (c) Philipp Fritsche <ph.fritsche@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pitch\Liform;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\FormView;

abstract class TransformationTestCase extends TestCase
{
    /**
     * @param FormView[] $children
     */
    protected function createFormView(
        string $formTypeClass,
        array $options = [],
        array $children = []
    ): FormView {
        $builder = Forms::createFormFactory()->createBuilder($formTypeClass, null, $options);
        $form = $builder->getForm();

        $view = $form->createView();
        foreach ($children as $key => $child) {
            $view->children[$key] = $child;
        }

        return $view;
    }
}
