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
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormView;
use Symfony\Contracts\Translation\TranslatorInterface;

class WidgetExtensionTest extends TransformationTestCase
{
    public function testBlockPrefixes()
    {
        $result = new TransformResult();
        $view = $this->createFormView(TextareaType::class);

        $extension = new WidgetExtension();
        $extension->apply($result, $view);

        $this->assertEquals(['_textarea', 'textarea', 'text', 'form'], $result->schema->widget);
    }

    public function testPreserveValue()
    {
        $result = new TransformResult();
        $result->schema->widget = 'foo';
        $view = $this->createFormView(TextareaType::class);

        $extension = new WidgetExtension();
        $extension->apply($result, $view);

        $this->assertEquals('foo', $result->schema->widget);
    }
}
