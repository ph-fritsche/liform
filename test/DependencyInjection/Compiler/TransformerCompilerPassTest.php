<?php
namespace Pitch\Liform\DependencyInjection\Compiler;

use stdClass;
use Pitch\Liform\Resolver;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Pitch\Liform\Transformer\TransformerInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TransformerCompilerPassTest extends TestCase
{
    public function testSetTransformer()
    {
        $container = new ContainerBuilder();

        $transformer = $this->createMock(TransformerInterface::class);
        $transformerDefinition = new Definition(get_class($transformer));
        $transformerDefinition->addTag(TransformerCompilerPass::TRANSFORMER_TAG, ['block' => 'fooBlock']);
        $container->setDefinition('foo', $transformerDefinition);

        $resolverDefinition = new Definition('bar');
        $container->setDefinition(Resolver::class, $resolverDefinition);

        $pass = new TransformerCompilerPass();
        $pass->process($container);
        
        $methodCalls = $resolverDefinition->getMethodCalls();
        $this->assertIsArray($methodCalls[0] ?? null);
        $this->assertEquals('setTransformer', $methodCalls[0][0]);
        $this->assertEquals('fooBlock', $methodCalls[0][1][0]);
        $this->assertInstanceOf(Reference::class, $methodCalls[0][1][1]);
        $this->assertEquals('foo', $methodCalls[0][1][1]);
    }

    public function testInvalidTag()
    {
        $container = new ContainerBuilder();

        $transformer = $this->createMock(TransformerInterface::class);
        $transformerDefinition = new Definition(get_class($transformer));
        $transformerDefinition->addTag(TransformerCompilerPass::TRANSFORMER_TAG);
        $container->setDefinition('foo', $transformerDefinition);

        $resolverDefinition = new Definition('bar');
        $container->setDefinition(Resolver::class, $resolverDefinition);

        $this->expectException(InvalidArgumentException::class);

        $pass = new TransformerCompilerPass();
        $pass->process($container);
    }

    public function testInvalidTransformer()
    {
        $container = new ContainerBuilder();

        $transformerDefinition = new Definition(stdClass::class);
        $transformerDefinition->addTag(TransformerCompilerPass::TRANSFORMER_TAG, ['block' => 'fooBlock']);
        $container->setDefinition('foo', $transformerDefinition);

        $resolverDefinition = new Definition('bar');
        $container->setDefinition(Resolver::class, $resolverDefinition);

        $this->expectException(InvalidArgumentException::class);

        $pass = new TransformerCompilerPass();
        $pass->process($container);
    }

    public function testNoResolver()
    {
        $container = $this->getMockBuilder(ContainerBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();
        /** @var ContainerBuilder $container */

        $pass = new TransformerCompilerPass();
        
        // expected to not do anthing
        $pass->process($container);

        $this->assertTrue(true);
    }
}
