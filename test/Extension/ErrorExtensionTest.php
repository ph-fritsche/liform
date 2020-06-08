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
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\FormError;
use Pitch\Liform\TransformationTestCase;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormView;

class ErrorExtensionTest extends TransformationTestCase
{
    public function testErrors()
    {
        $result = new TransformResult();

        $form = Forms::createFormFactory()->createBuilder(FormType::class)->getForm();
        $form->addError(new FormError('foo'));
        $view = $form->createView();

        $extension = new ErrorExtension();

        $extension->apply($result, $view);

        $this->assertTrue(isset($result->meta['errors']));
        $this->assertEquals(['foo'], $result->meta['errors']);
    }

    public function testIncompleteViewVars()
    {
        $result = new TransformResult();
        $view = new FormView();

        $extension = new ErrorExtension();

        $extension->apply($result, $view);

        // should do nothing

        $this->assertTrue(true);
    }
}
