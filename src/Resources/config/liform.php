<?php
namespace Pitch\Liform\Resources\config;

use Pitch\Liform\Liform;
use Pitch\Liform\LiformInterface;
use Pitch\Liform\Resolver;
use Pitch\Liform\ResolverInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->defaults()
            ->autowire()
        ->set(Resolver::class)
        ->alias(ResolverInterface::class, Resolver::class)
        ->set(LiformInterface::class, Liform::class)
    ;
};
