<?php
namespace Pitch\Liform\Resources\config;

use Pitch\Liform\Responder\LiformResponseHandler;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container) {
    $services = $container->services()->defaults()->autowire();

    $services->set(LiformResponseHandler::class)
        ->tag('pitch_adr.responder', ['priority' => -1]);
};
