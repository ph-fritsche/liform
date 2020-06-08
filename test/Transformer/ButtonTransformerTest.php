<?php

/*
 * This file is part of the Pitch\Liform package.
 *
 * (c) Philipp Fritsche <ph.fritsche@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pitch\Liform\Transformer;

use Pitch\Liform\TransformationTestCase;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;

class ButtonTransformerTest extends TransformationTestCase
{
    public function testButton()
    {
        $view = $this->createFormView(ButtonType::class);
        
        $transformer = new ButtonTransformer();
        $result = $transformer->transform($view);
        
        // The ButtonTransformer is merely a dummy as there is no specific data in the FormView.
        $this->assertTrue(true);
    }
}
