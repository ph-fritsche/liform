<?php

/*
 * This file is part of the Pitch\Liform package.
 *
 * (c) Philipp Fritsche <ph.fritsche@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pitch\Liform\DependencyInjection\Compiler;

use stdClass;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Pitch\Liform\LiformInterface;
use Pitch\Liform\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ExtensionCompilerPassTest extends TestCase
{
    public function testAddExtensions()
    {
        $container = new ContainerBuilder();

        $extension = $this->createMock(ExtensionInterface::class);
        $extensionDefinition = new Definition(get_class($extension));
        $extensionDefinition->addTag(ExtensionCompilerPass::EXTENSION_TAG);
        $container->setDefinition('foo', $extensionDefinition);

        $liformDefinition = new Definition('bar');
        $container->setDefinition(LiformInterface::class, $liformDefinition);

        $pass = new ExtensionCompilerPass();
        $pass->process($container);
        
        $methodCalls = $liformDefinition->getMethodCalls();
        $this->assertIsArray($methodCalls[0] ?? null);
        $this->assertEquals('addExtension', $methodCalls[0][0]);
        $this->assertInstanceOf(Reference::class, $methodCalls[0][1][0]);
        $this->assertEquals('foo', $methodCalls[0][1][0]);
    }

    public function testInvalidExtension()
    {
        $container = new ContainerBuilder();

        $extensionDefinition = new Definition(stdClass::class);
        $extensionDefinition->addTag(ExtensionCompilerPass::EXTENSION_TAG);
        $container->setDefinition('foo', $extensionDefinition);

        $liformDefinition = new Definition('bar');
        $container->setDefinition(LiformInterface::class, $liformDefinition);

        $this->expectException(InvalidArgumentException::class);

        $pass = new ExtensionCompilerPass();
        $pass->process($container);
    }

    public function testNoLiform()
    {
        $container = $this->getMockBuilder(ContainerBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();
        /** @var ContainerBuilder $container */

        $pass = new ExtensionCompilerPass();
        
        // expected to not do anthing
        $pass->process($container);

        $this->assertTrue(true);
    }
}
