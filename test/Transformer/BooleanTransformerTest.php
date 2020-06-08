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
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class BooleanTransformerTest extends TransformationTestCase
{
    public function testButton()
    {
        $view = $this->createFormView(CheckboxType::class);

        $transformer = new BooleanTransformer();
        $result = $transformer->transform($view);

        $this->assertEquals('boolean', $result->schema->type);
    }
}
