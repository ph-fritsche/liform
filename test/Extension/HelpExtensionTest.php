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

class HelpExtensionTest extends TransformationTestCase
{
    public function testTranslateHelp()
    {
        $result = new TransformResult();
        $view = $this->createFormView(FormType::class, [
            'help' => 'foo',
        ]);

        $translator = $this->createMock(TranslatorInterface::class);
        $translator->expects($this->once())->method('trans')
            ->with('foo')
            ->willReturn('bar');

        $extension = new HelpExtension($translator);

        $extension->apply($result, $view);

        $this->assertEquals('bar', $result->schema->description);
    }

    public function testNoHelp()
    {
        $result = new TransformResult();
        $result->schema->description = 'foo';
        $view = $this->createFormView(FormType::class);

        $extension = new HelpExtension();

        $extension->apply($result, $view);

        $this->assertEquals('foo', $result->schema->description);
    }
}
