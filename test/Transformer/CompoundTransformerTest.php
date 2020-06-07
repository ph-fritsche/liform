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

use Pitch\Liform\LiformInterface;
use Symfony\Component\Form\FormView;
use Pitch\Liform\TransformationTestCase;
use Pitch\Liform\TransformResult;
use stdClass;
use Symfony\Component\Form\Extension\Core\Type\FormType;

/**
 * @author Nacho Martín <nacho@limenius.com>
 * @author Philipp Fritsche <ph.fritsche@gmail.com>
 */
class CompoundTransformerTest extends TransformationTestCase
{
    public function testObjectWithProperties()
    {
        $childViews = ['foo' => new FormView(), 'bar' => new FormView()];
        $view = $this->createFormView(FormType::class, [], $childViews);

        $liform = $this->createMock(LiformInterface::class);
        $childResults = array_map(function () {
            $r = new TransformResult();
            $r->value = new stdClass();
            return $r;
        }, $childViews);
        $liform->expects($this->exactly(2))->method('transform')
            ->withConsecutive(...array_map(fn($v) => [$v], array_values($childViews)))
            ->willReturn(...array_values($childResults));
        /** @var LiformInterface $liform */

        $transformer = new CompoundTransformer($liform);
        $result = $transformer->transform($view);

        $this->assertEquals('object', $result->schema->type);
        $this->assertEquals(array_keys($childViews), $result->schema->getPropertyNames());
        $this->assertIsObject($result->value);

        $properties = $result->schema->getProperties();

        foreach (array_keys($childViews) as $i => $childKey) {
            $this->assertArrayHasKey($childKey, $properties);
            $this->assertEquals($i, $properties[$childKey]['propertyOrder']);
            $this->assertSame($childResults[$childKey]->schema, $properties[$childKey]);

            $this->assertSame($childResults[$childKey]->meta, $result->meta->getPropertyMeta($childKey));
            $this->assertSame($childResults[$childKey]->value, $result->value->{$childKey});
        }
    }
}
