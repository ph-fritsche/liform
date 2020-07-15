# Setup

## Without [Symfony Flex](https://symfony.com/doc/current/setup/flex.html)

`PitchLiformBundle` needs to be activated in your Symfony application.

```php
// config/bundles.php

return [
    // ...
    Pitch\Liform\PitchLiformBundle::class => ['all' => true],
];
```

## Without [Symfony Service Container](https://symfony.com/doc/current/service_container.html)

```php
$resolver = new \Pitch\Liform\Resolver();
$resolver->setTransformer('text', new \Pitch\Liform\Transformer\StringTransformer());
$resolver->setTransformer('number', new \Pitch\Liform\Transformer\NumberTransformer());
/* ... */

$liform = new \Pitch\Liform\Liform($resolver);
$liform->addExtension(new \Pitch\Liform\Extension\ValueExtension());
$liform->addExtension(new \Pitch\Liform\Extension\LabelExtension());
/* ... */

return $liform;
```
