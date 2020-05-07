<?php

/*
 * This file is part of the Limenius\LiformBundle package.
 *
 * (c) Limenius <https://github.com/Limenius/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pitch\Liform\DependencyInjection\Compiler;

use Pitch\Liform\LiformInterface;
use Pitch\Liform\Transformer\ExtensionInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * @author Nacho Martín <nacho@limenius.com>
 */
class ExtensionCompilerPass implements CompilerPassInterface
{
    const EXTENSION_TAG = 'liform.extension';

    /**
     * @inheritdoc
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(LiformInterface::class)) {
            return;
        }

        $liform = $container->getDefinition(LiformInterface::class);

        foreach ($container->findTaggedServiceIds(self::EXTENSION_TAG) as $id => $tags) {
            $extension = $container->getDefinition($id);

            if (!isset(class_implements($extension->getClass())[ExtensionInterface::class])) {
                throw new \InvalidArgumentException(sprintf(
                    "The service %s was tagged as a '%s' but does not implement the mandatory %s",
                    $id,
                    self::EXTENSION_TAG,
                    ExtensionInterface::class
                ));
            }

            $liform->addMethodCall('addExtension', [new Reference($id)]);
        }
    }
}
