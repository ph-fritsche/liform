<?php

/*
 * This file is part of the Limenius\LiformBundle package.
 *
 * (c) Limenius <https://github.com/Limenius/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pitch\LiformBundle\DependencyInjection\Compiler;

use Pitch\Liform\ResolverInterface;
use Pitch\Liform\Transformer\TransformerInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Nacho Martín <nacho@limenius.com>
 */
class TransformerCompilerPass implements CompilerPassInterface
{
    const TRANSFORMER_TAG = 'liform.transformer';

    /**
     * @inheritdoc
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(ResolverInterface::class)) {
            return;
        }

        $resolver = $container->getDefinition(ResolverInterface::class);

        foreach ($container->findTaggedServiceIds(self::TRANSFORMER_TAG) as $id => $tags) {
            $transformer = $container->getDefinition($id);

            if (!isset(class_implements($transformer->getClass())[TransformerInterface::class])) {
                throw new \InvalidArgumentException(sprintf(
                    "The service %s was tagged as a '%s' but does not implement the mandatory %s",
                    $id,
                    self::TRANSFORMER_TAG,
                    TransformerInterface::class
                ));
            }

            foreach ($tags as $tag) {
                if (!isset($tag['form_type'])) {
                    throw new \InvalidArgumentException(sprintf(
                        "The service %s was tagged as a '%s' but does not specify the mandatory 'form_type' option.",
                        $id,
                        self::TRANSFORMER_TAG
                    ));
                }

                $resolver->addMethodCall('setTransformer', [
                    $tag['form_type'],
                    new Reference($id),
                    $tag['widget'] ?? null
                ]);
            }
        }
    }
}
