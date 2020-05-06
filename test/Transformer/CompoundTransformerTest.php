<?php

/*
 * This file is part of the Limenius\Liform package.
 *
 * (c) Limenius <https://github.com/Limenius/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pitch\Liform\Liform\Transformer;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Tests\AbstractFormTest;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FormType;

use Pitch\Liform\Transformer\CompoundTransformer;
use Pitch\Liform\Transformer\StringTransformer;
use Pitch\Liform\Resolver;
use Pitch\Liform\LiformTestCase;

/**
 * @author Nacho Martín <nacho@limenius.com>
 *
 * @see TypeTestCase
 */
class CompoundTransformerTest extends LiformTestCase
{
    public function testOrder()
    {
        $form = $this->factory->create(FormType::class)
            ->add('firstName', TextType::class)
            ->add('secondName', TextType::class);
        $resolver = new Resolver();
        $resolver->setTransformer('text', new StringTransformer($this->translator));
        $transformer = new CompoundTransformer($this->translator, null, $resolver);
        $transformed = $transformer->transform($form);
        $this->assertTrue(is_array($transformed));
        $this->assertEquals(1, $transformed['properties']['firstName']['propertyOrder']);
        $this->assertEquals(2, $transformed['properties']['secondName']['propertyOrder']);
    }
}
