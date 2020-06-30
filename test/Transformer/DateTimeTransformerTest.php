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

use Pitch\Liform\LiformInterface;
use Pitch\Liform\TransformationTestCase;
use Pitch\Liform\TransformResult;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\WeekType;

class DateTimeTransformerTest extends TransformationTestCase
{
    public function testCompound()
    {
        $view = $this->createFormView(DateTimeType::class);

        $liform = $this->createMock(LiformInterface::class);
        $liform->method('transform')
            ->willReturn(new TransformResult());
        /** @var LiformInterface $liform */

        $transformer = new DateTimeTransformer($liform);
        $result = $transformer->transform($view);

        $this->assertEquals('object', $result->schema->type);
    }

    public function testDateTime()
    {
        $view = $this->createFormView(DateTimeType::class, [
            'widget' => 'single_text',
        ]);

        $liform = $this->createMock(LiformInterface::class);
        /** @var LiformInterface $liform */
        $transformer = new DateTimeTransformer($liform);
        $result = $transformer->transform($view);

        $this->assertEquals('string', $result->schema->type);
        $this->assertIsString($result->schema->pattern);
        $this->assertEquals(1, preg_match('/' . $result->schema->pattern . '/', '2041-12-11T10:09'));
    }

    public function testDateTimeText()
    {
        $view = $this->createFormView(DateTimeType::class, [
            'widget' => 'single_text',
            'html5' => false,
        ]);

        $liform = $this->createMock(LiformInterface::class);
        /** @var LiformInterface $liform */
        $transformer = new DateTimeTransformer($liform);
        $result = $transformer->transform($view);

        $this->assertEquals('string', $result->schema->type);
        $this->assertIsString($result->schema->pattern);
        $this->assertEquals(1, preg_match('/' . $result->schema->pattern . '/', '2041-12-11T10'));
        $this->assertEquals(1, preg_match('/' . $result->schema->pattern . '/', '2041-12-11T10+02:00'));
        $this->assertEquals(1, preg_match('/' . $result->schema->pattern . '/', '2041-12-11T10:09'));
        $this->assertEquals(1, preg_match('/' . $result->schema->pattern . '/', '2041-12-11T10:09+02:00'));
        $this->assertEquals(1, preg_match('/' . $result->schema->pattern . '/', '2041-12-11T10:09:08'));
        $this->assertEquals(1, preg_match('/' . $result->schema->pattern . '/', '2041-12-11T10:09:08+02:00'));
    }

    public function testDate()
    {
        $view = $this->createFormView(DateType::class, [
            'widget' => 'single_text',
        ]);

        $liform = $this->createMock(LiformInterface::class);
        /** @var LiformInterface $liform */
        $transformer = new DateTimeTransformer($liform);
        $result = $transformer->transform($view);

        $this->assertEquals('string', $result->schema->type);
        $this->assertIsString($result->schema->format);
        $this->assertEquals('date', $result->schema->format);
    }

    public function testDateText()
    {
        $view = $this->createFormView(DateType::class, [
            'widget' => 'single_text',
            'html5' => false,
        ]);

        $liform = $this->createMock(LiformInterface::class);
        /** @var LiformInterface $liform */
        $transformer = new DateTimeTransformer($liform);
        $result = $transformer->transform($view);

        $this->assertEquals('string', $result->schema->type);
        $this->assertIsString($result->schema->pattern);
        $this->assertEquals(1, preg_match('/' . $result->schema->pattern . '/', '2041-12-11'));
    }

    public function testTime()
    {
        $view = $this->createFormView(TimeType::class, [
            'widget' => 'single_text',
        ]);

        $liform = $this->createMock(LiformInterface::class);
        /** @var LiformInterface $liform */
        $transformer = new DateTimeTransformer($liform);
        $result = $transformer->transform($view);

        $this->assertEquals('string', $result->schema->type);
        $this->assertIsString($result->schema->pattern);
        $this->assertEquals(1, preg_match('/' . $result->schema->pattern . '/', '10:09'));
    }

    public function testTimeText()
    {
        $view = $this->createFormView(TimeType::class, [
            'widget' => 'single_text',
            'html5' => false,
        ]);

        $liform = $this->createMock(LiformInterface::class);
        /** @var LiformInterface $liform */
        $transformer = new DateTimeTransformer($liform);
        $result = $transformer->transform($view);

        $this->assertEquals('string', $result->schema->type);
        $this->assertIsString($result->schema->pattern);
        $this->assertEquals(1, preg_match('/' . $result->schema->pattern . '/', '10'));
        $this->assertEquals(1, preg_match('/' . $result->schema->pattern . '/', '10+02:00'));
        $this->assertEquals(1, preg_match('/' . $result->schema->pattern . '/', '10:09'));
        $this->assertEquals(1, preg_match('/' . $result->schema->pattern . '/', '10:09+02:00'));
        $this->assertEquals(1, preg_match('/' . $result->schema->pattern . '/', '10:09:08'));
        $this->assertEquals(1, preg_match('/' . $result->schema->pattern . '/', '10:09:08+02:00'));
    }

    public function testDateTimeInterval()
    {
        $view = $this->createFormView(DateIntervalType::class, [
            'widget' => 'single_text',
        ]);

        $liform = $this->createMock(LiformInterface::class);
        /** @var LiformInterface $liform */
        $transformer = new DateTimeTransformer($liform);
        $result = $transformer->transform($view);

        $this->assertEquals('string', $result->schema->type);
        $this->assertIsString($result->schema->pattern);
        $this->assertEquals(1, preg_match('/' . $result->schema->pattern . '/', '-P1Y2M3DT10H12M14S'));
    }

    public function testWeek()
    {
        $view = $this->createFormView(WeekType::class, [
            'widget' => 'single_text',
        ]);

        $liform = $this->createMock(LiformInterface::class);
        /** @var LiformInterface $liform */
        $transformer = new DateTimeTransformer($liform);
        $result = $transformer->transform($view);

        $this->assertEquals('string', $result->schema->type);
        $this->assertIsString($result->schema->pattern);
        $this->assertEquals(1, preg_match('/' . $result->schema->pattern . '/', '2041-W12'));
    }
}
