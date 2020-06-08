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

use Pitch\Liform\TransformationTestCase;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;

/**
 * @author Philipp Fritsche <ph.fritsche@gmail.com>
 */
class NumberTransformerTest extends TransformationTestCase
{
    public function testInteger()
    {
        $view = $this->createFormView(IntegerType::class);

        $transformer = new NumberTransformer();
        $result = $transformer->transform($view);

        $this->assertEquals('integer', $result->schema->type);
    }

    public function testNumber()
    {
        $view = $this->createFormView(NumberType::class);

        $transformer = new NumberTransformer();
        $result = $transformer->transform($view);

        $this->assertEquals('number', $result->schema->type);
    }

    public function testConstraints()
    {
        $view = $this->createFormView(NumberType::class, ['attr' => [
            'min' => 1,
            'max' => 20,
            'step' => 5,
        ]]);

        $transformer = new NumberTransformer();
        $result = $transformer->transform($view);

        $this->assertEquals(1, $result->schema->minimum);
        $this->assertEquals(20, $result->schema->maximum);

        // non-standard
        $this->assertEquals(5, $result->schema->step);
    }

    public function testSymbol()
    {
        $view = $this->createFormView(PercentType::class);

        $transformer = new NumberTransformer();
        $result = $transformer->transform($view);

        $this->assertEquals('%', $result->schema->symbol);
    }

    public function testCurrencySymbol()
    {
        $view = $this->createFormView(MoneyType::class);

        $transformer = new NumberTransformer();
        $result = $transformer->transform($view);

        $this->assertEquals('€', $result->schema->symbol);
    }
}
