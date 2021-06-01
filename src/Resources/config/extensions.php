<?php

namespace Pitch\Liform\Resources\config;

use Pitch\Liform\DependencyInjection\Compiler\ExtensionCompilerPass;
use Pitch\Liform\Extension\AttrExtension;
use Pitch\Liform\Extension\DisabledExtension;
use Pitch\Liform\Extension\ErrorExtension;
use Pitch\Liform\Extension\HelpExtension;
use Pitch\Liform\Extension\LabelExtension;
use Pitch\Liform\Extension\NameExtension;
use Pitch\Liform\Extension\ValueExtension;
use Pitch\Liform\Extension\WidgetExtension;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container) {
    $services = $container->services()->defaults()->autowire();

    foreach ([
        AttrExtension::class,
        DisabledExtension::class,
        ErrorExtension::class,
        HelpExtension::class,
        LabelExtension::class,
        NameExtension::class,
        ValueExtension::class,
        WidgetExtension::class,
    ] as $e) {
        $services->set($e)->tag(ExtensionCompilerPass::EXTENSION_TAG);
    }
};
