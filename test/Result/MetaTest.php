<?php
namespace Pitch\Liform\Result;

use PHPUnit\Framework\TestCase;

class MetaTest extends TestCase
{
    public function testArrayAccess()
    {
        $meta = new Meta();

        $this->assertFalse(isset($meta['foo']));
        
        $meta['foo'] = 'bar';
        
        $this->assertTrue(isset($meta['foo']));
        $this->assertEquals('bar', $meta['foo']);

        unset($meta['foo']);

        $this->assertFalse(isset($meta['foo']));
    }

    public function testPropertyMeta()
    {
        $meta = new Meta();
        $fooMeta = new Meta();

        $this->assertFalse($meta->hasPropertyMeta('foo'));

        $meta->setPropertyMeta('foo', $fooMeta);

        $this->assertTrue($meta->hasPropertyMeta('foo'));
        $this->assertSame($fooMeta, $meta->getPropertyMeta('foo'));
    }

    public function testFlattenedJson()
    {
        $meta = new Meta();
        $fooMeta = new Meta();
        $barMeta = new Meta();

        $meta->setPropertyMeta('foo', $fooMeta);
        $fooMeta->setPropertyMeta('bar', $barMeta);

        $meta['someKey'] = 'someValue';
        $fooMeta['someKey'] = 'someOtherValue';
        $barMeta['anotherKey'] = 'anotherValue';

        $unserializedAsArray = json_decode(json_encode($meta), true);

        $this->assertEquals([
            'someKey' => [
                '' => 'someValue',
                'foo' => 'someOtherValue',
            ],
            'anotherKey' => [
                'foo.bar' => 'anotherValue',
            ],
        ], $unserializedAsArray);
    }
}
