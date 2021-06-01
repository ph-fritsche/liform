<?php

namespace Pitch\Liform\Resources\config;

use Pitch\Liform\DependencyInjection\Compiler\TransformerCompilerPass;
use Pitch\Liform\Transformer\ArrayTransformer;
use Pitch\Liform\Transformer\BooleanTransformer;
use Pitch\Liform\Transformer\ButtonTransformer;
use Pitch\Liform\Transformer\ChoiceTransformer;
use Pitch\Liform\Transformer\CompoundTransformer;
use Pitch\Liform\Transformer\DateTimeTransformer;
use Pitch\Liform\Transformer\NumberTransformer;
use Pitch\Liform\Transformer\StringTransformer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container) {
    $services = $container->services()->defaults()->autowire();

    foreach ([
        ArrayTransformer::class => [
            'collection',
        ],
        BooleanTransformer::class => [
            'checkbox',
        ],
        ButtonTransformer::class => [
            'button',
        ],
        ChoiceTransformer::class => [
            'choice',
        ],
        CompoundTransformer::class => [
            'form',
        ],
        DateTimeTransformer::class => [
            'datetime',
            'dateinterval',
            'date',
            'time',
            'week',
        ],
        NumberTransformer::class => [
            'number',
            'integer',
            'range',
            'money',
            'percent',
        ],
        StringTransformer::class => [
            'text',
            'textarea',
            'color',
        ],
    ] as $e => $blocks) {
        $s = $services->set($e);
        foreach ($blocks as $b) {
            $s->tag(TransformerCompilerPass::TRANSFORMER_TAG, ['block' => $b]);
        }
    }
};
