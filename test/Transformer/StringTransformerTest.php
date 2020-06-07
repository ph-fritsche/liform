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
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * @author Nacho Martín <nacho@limenius.com>
 * @author Philipp Fritsche <ph.fritsche@gmail.com>
 */
class StringTransformerTest extends TransformationTestCase
{
    public function testString()
    {
        $view = $this->createFormView(TextType::class);

        $transformer = new StringTransformer();
        $result = $transformer->transform($view);

        $this->assertEquals('string', $result->schema->type);
    }

    public function testPattern()
    {
        $view = $this->createFormView(TextType::class, ['attr' => [
            'pattern' => '.{5,}',
        ]]);

        $transformer = new StringTransformer();
        $result = $transformer->transform($view);

        $this->assertEquals('.{5,}', $result->schema->pattern);
    }
}
