<?php
namespace Pitch\Liform;

use PHPUnit\Framework\TestCase;
use Pitch\Liform\DependencyInjection\Compiler\ExtensionCompilerPass;
use Pitch\Liform\DependencyInjection\Compiler\TransformerCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class PitchLiformBundleTest extends TestCase
{
    public function testBuild()
    {
        $container = new ContainerBuilder();

        $bundle = new PitchLiformBundle();
        $bundle->build($container);

        $compilerPasses = \array_map(fn($v) => get_class($v), $container->getCompilerPassConfig()->getPasses());

        $this->assertContains(ExtensionCompilerPass::class, $compilerPasses);
        $this->assertContains(TransformerCompilerPass::class, $compilerPasses);
    }
}
