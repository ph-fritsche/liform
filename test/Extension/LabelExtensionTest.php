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
use Symfony\Component\Form\FormView;
use Symfony\Contracts\Translation\TranslatorInterface;

class LabelExtensionTest extends TransformationTestCase
{
    public function testTranslateLabel()
    {
        $result = new TransformResult();
        $view = $this->createFormView(FormType::class, [
            'label' => 'foo',
        ]);

        $translator = $this->createMock(TranslatorInterface::class);
        $translator->expects($this->once())->method('trans')
            ->with('foo')
            ->willReturn('bar');

        $extension = new LabelExtension($translator);

        $extension->apply($result, $view);

        $this->assertEquals('bar', $result->schema->title);
    }

    public function testLabelFromFormat()
    {
        $result = new TransformResult();
        $view = $this->createFormView(FormType::class, [
            'label_format' => 'foo%name%',
        ]);

        $extension = new LabelExtension();

        $extension->apply($result, $view);

        $this->assertEquals('fooform', $result->schema->title);
    }

    public function testLabelFromName()
    {
        $result = new TransformResult();
        $view = $this->createFormView(FormType::class);

        $extension = new LabelExtension();

        $extension->apply($result, $view);

        $this->assertEquals('form', $result->schema->title);
    }

    public function testNoLabel()
    {
        $result = new TransformResult();
        $view = new FormView();

        $extension = new LabelExtension();

        $extension->apply($result, $view);

        $this->assertEquals(null, $result->schema->title);
    }

    public function testPlaceholder()
    {
        $result = new TransformResult();
        $view = $this->createFormView(FormType::class, [
            'label' => '__foo__',
        ]);

        $extension = new LabelExtension();

        $extension->apply($result, $view);

        $this->assertEquals(null, $result->schema->title);
    }
}
