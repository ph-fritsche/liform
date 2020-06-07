<?php
namespace Pitch\Liform\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Pitch\Liform\LiformInterface;
use Pitch\Liform\ResolverInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class PitchLiformExtensionTest extends TestCase
{
    public function testLoadConfig()
    {
        $services = [];

        $container = $this->createMock(ContainerBuilder::class);
        $container->expects($this->any())
            ->method('setAlias')
            ->willReturnCallback(function ($id, $alias) use (&$services) {
                $services[$id] = $alias;
            });
        $container->expects($this->any())
            ->method('setDefinition')
            ->willReturnCallback(function ($id, $definition) use (&$services) {
                $services[$id] = $definition;
            });

        $extension = new PitchLiformExtension();
        $extension->load([], $container);

        $this->assertArrayHasKey(LiformInterface::class, $services);
        $this->assertArrayHasKey(ResolverInterface::class, $services);
    }
}
