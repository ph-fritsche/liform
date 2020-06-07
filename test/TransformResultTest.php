<?php
namespace Pitch\Liform;

use PHPUnit\Framework\TestCase;
use Pitch\Liform\Result\Meta;
use Swaggest\JsonSchema\Schema;

class TransformResultTest extends TestCase
{
    public function testInit()
    {
        $result = new TransformResult();

        $this->assertInstanceOf(Schema::class, $result->schema);
        $this->assertInstanceOf(Meta::class, $result->meta);
    }

    public function testHasValue()
    {
        $result = new TransformResult();

        $this->assertFalse($result->hasValue());

        $result->value = null;

        $this->assertTrue($result->hasValue());
    }
}
