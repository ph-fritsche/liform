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

use Pitch\Liform\TransformationTestCase;
use Pitch\Liform\TransformResult;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Contracts\Translation\TranslatorInterface;

class AttrExtensionTest extends TransformationTestCase
{
    public function testAttr()
    {
        $result = new TransformResult();
        $view = $this->createFormView(FormType::class, [
            'attr' => ['foo' => 'bar']
        ]);

        $extension = new AttrExtension();

        $extension->apply($result, $view);

        $this->assertEquals(['foo' => 'bar'], $result->schema->attr);
    }

    public function testTranslatePlaceholder()
    {
        $result = new TransformResult();
        $view = $this->createFormView(FormType::class, [
            'attr' => ['placeholder' => 'foo'],
        ]);

        $translator = $this->createMock(TranslatorInterface::class);
        $translator->expects($this->once())->method('trans')
            ->with('foo')
            ->willReturn('bar');

        $extension = new AttrExtension($translator);

        $extension->apply($result, $view);

        $this->assertEquals('bar', $result->schema->placeholder);
    }
}
