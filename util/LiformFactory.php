<?php
namespace Pitch\Liform;

use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Contracts\Translation\TranslatorInterface;

class LiformFactory
{
    public function createLiform(): LiformInterface
    {
        $container = new ContainerBuilder();

        $bundle = new PitchLiformBundle();
        $extension = $bundle->getContainerExtension();

        $bundle->build($container);
        $container->registerExtension($extension);
        $container->loadFromExtension($extension->getAlias());

        $container->setDefinition(TranslatorInterface::class, new Definition(TranslatorMock::class));

        $container->setAlias('liform', new Alias(LiformInterface::class, true));
        $container->compile();

        return $container->get('liform');
    }
}
