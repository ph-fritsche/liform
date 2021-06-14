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
use Symfony\Component\Form\FormView;

class ValueExtensionTest extends TransformationTestCase
{
    public function testCompound()
    {
        $result = new TransformResult();
        $view = new FormView();
        $view->vars['value'] = 'foo';
        $view->vars['compound'] = true;

        $extension = new ValueExtension();
        $extension->apply($result, $view);

        $this->assertFalse($result->hasValue());
    }

    public function testValue()
    {
        $result = new TransformResult();
        $view = new FormView();
        $view->vars['value'] = 'foo';

        $extension = new ValueExtension();
        $extension->apply($result, $view);

        $this->assertTrue($result->hasValue());
        $this->assertEquals('foo', $result->value);
    }

    public function testTypeCast()
    {
        $result = new TransformResult();
        $view = new FormView();
        $view->vars['value'] = '1.2';

        $extension = new ValueExtension();

        $result->schema->type = 'boolean';
        $extension->apply($result, $view);

        $this->assertIsBool($result->value);

        $result->schema->type = 'integer';
        $extension->apply($result, $view);

        $this->assertIsInt($result->value);

        $result->schema->type = 'number';
        $extension->apply($result, $view);

        $this->assertIsFloat($result->value);
    }

    public function testChecked()
    {
        $extension = new ValueExtension();
        $result = new TransformResult();
        $view = new FormView();
        $view->vars['value'] = '1';
        
        $view->vars['checked'] = false;
        $extension->apply($result, $view);

        $this->assertFalse($result->hasValue());

        $view->vars['checked'] = true;
        $extension->apply($result, $view);

        $this->assertTrue($result->hasValue());
    }
}
