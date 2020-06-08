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

class DisabledExtensionTest extends TransformationTestCase
{
    public function testDisabled()
    {
        $result = new TransformResult();
        $view = $this->createFormView(FormType::class, [
            'disabled' => true,
        ]);

        $extension = new DisabledExtension();

        $extension->apply($result, $view);

        $this->assertTrue($result->schema->disabled);
    }
}
