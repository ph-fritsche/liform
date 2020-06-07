<?php

/*
 * This file is part of the Pitch\Liform package.
 *
 * (c) Philipp Fritsche <ph.fritsche@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pitch\Liform\Transformer;

use stdClass;
use Pitch\Liform\LiformInterface;
use Pitch\Liform\TransformResult;
use Symfony\Component\Form\FormView;
use Pitch\Liform\TransformationTestCase;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ArrayTransformerTest extends TransformationTestCase
{
    public function testCollection()
    {
        $childViews = [new FormView(), new FormView()];
        $view = $this->createFormView(CollectionType::class, [], $childViews);

        $liform = $this->createMock(LiformInterface::class);
        $childResults = array_map(function () {
            $r = new TransformResult();
            $r->value = new stdClass();
            return $r;
        }, $childViews);
        $liform->expects($this->exactly(2))->method('transform')
            ->withConsecutive(...array_map(fn($v) => [$v], $childViews))
            ->willReturn(...$childResults);
        /** @var LiformInterface $liform */

        $transformer = new ArrayTransformer($liform);
        $result = $transformer->transform($view);

        $this->assertEquals('array', $result->schema->type);
        $this->assertIsArray($result->value);

        $this->assertEquals($childResults[0]->schema, $result->schema->items);
        $this->assertSame($childResults[0]->value, $result->value[0]);
        $this->assertSame($childResults[1]->value, $result->value[1]);
    }

    public function testCollectionDifferentItems()
    {
        $childViews = [new FormView(), new FormView()];
        $view = $this->createFormView(CollectionType::class, [], $childViews);

        $liform = $this->createMock(LiformInterface::class);
        $childResults = array_map(function ($v, $i) {
            $r = new TransformResult();
            $r->schema->type = $i ? 'number' : 'string';
            return $r;
        }, $childViews, array_keys($childViews));
        $liform->expects($this->exactly(2))->method('transform')
            ->withConsecutive(...array_map(fn($v) => [$v], $childViews))
            ->willReturn(...$childResults);
        /** @var LiformInterface $liform */

        $transformer = new ArrayTransformer($liform);
        $result = $transformer->transform($view);

        $this->assertIsArray($result->schema->items);
        $this->assertSame($childResults[0]->schema, $result->schema->items[0]);
        $this->assertSame($childResults[1]->schema, $result->schema->items[1]);
    }

    public function testCollectionPrototype()
    {
        $view = $this->createFormView(CollectionType::class, [
            'prototype' => true,
            'allow_add' => true,
        ]);

        $liform = $this->createMock(LiformInterface::class);
        $prototypeResult = new TransformResult();
        $prototypeResult->value = 'foo';
        $liform->expects($this->once(1))->method('transform')
            ->willReturn($prototypeResult);
        /** @var LiformInterface $liform */

        $transformer = new ArrayTransformer($liform);
        $result = $transformer->transform($view);

        $this->assertSame($prototypeResult->schema, $result->schema->items);
        $this->assertEquals('foo', $result->schema->prototypeValue);
    }

    public function testCollectionPrototypeWithItems()
    {
        $childViews = [new FormView(), new FormView()];
        $view = $this->createFormView(CollectionType::class, [
            'prototype' => true,
            'allow_add' => true,
        ], $childViews);

        $liform = $this->createMock(LiformInterface::class);
        $prototypeResult = new TransformResult();
        $prototypeResult->schema->type = 'fooType';
        $prototypeResult->value = 'foo';
        $liform->expects($this->exactly(3))->method('transform')
            ->willReturn($prototypeResult, new TransformResult(), new TransformResult());
        /** @var LiformInterface $liform */

        $transformer = new ArrayTransformer($liform);
        $result = $transformer->transform($view);

        $this->assertSame($prototypeResult->schema, $result->schema->additionalItems);
        $this->assertEquals('foo', $result->schema->prototypeValue);
    }

    public function testAllowAdd()
    {
        $view = $this->createFormView(CollectionType::class, [
            'allow_add' => true,
        ]);

        $liform = $this->createMock(LiformInterface::class);
        $liform->method('transform')->willReturn(new TransformResult());

        $transformer = new ArrayTransformer($liform);
        $result = $transformer->transform($view);

        $this->assertTrue($result->schema->allowAdd);
    }

    public function testAllowDelete()
    {
        $view = $this->createFormView(CollectionType::class, [
            'allow_delete' => true,
        ]);

        $liform = $this->createMock(LiformInterface::class);

        $transformer = new ArrayTransformer($liform);
        $result = $transformer->transform($view);

        $this->assertTrue($result->schema->allowDelete);
    }
}
