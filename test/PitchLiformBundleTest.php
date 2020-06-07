<?php

/*
 * This file is part of the Pitch\Liform package.
 *
 * (c) Philipp Fritsche <ph.fritsche@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
