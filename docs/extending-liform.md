# Extending Liform

If you need to transform [custom Form Types](https://symfony.com/doc/current/form/create_custom_field_type.html) and/or need extra data to be passed to your UI libraries you can easily extend the transformation with your own methods.

## Transformer

A __Transformer__ in __Liform__ provides the [JSON](https://json.org) representation for a [Form View](https://symfony.com/doc/current/forms.html#rendering-forms).
It must return a `TransformResult` that should include all information that is specific for the rendered Form Types.

```php
use Pitch\Liform\Transformer\TransformerInterface;
use Pitch\Liform\TransformResult;
use Symfony\Component\Form\FormView;

class MyTransformer implements TransformerInterface
{
    public function transform(FormView $view): TransformResult
    {
        //...
    }
}
```

## Resolver

__Liform__ delegates the decision which __Tranformer__ it should use to the __Resolver__.

The built-in __Resolver__ traverses `block_prefixes` like a tranformation per [Twig templates](https://symfony.com/doc/current/form/form_customization.html) would.

You can write your own __Transformers__ and bind them to a block prefix.
```yml
services:
    # ...
    App\MySpecialTransformer:
        tags:
            - { name: liform.transformer, block: my_block_prefix }
    App\MyTextFieldTransformer:
        tags:
            - { name: liform.transformer, block: text }
```

If you want to decide which __Transformer__ another way you can replace the __Resolver__.

```php
namespace App;

use Pitch\Liform\Transformer\TransformerInterface;
use Symfony\Component\Form\FormView;

class MyLiformResolver implements ResolverInterface
{
    public function resolve(FormView $view): TransformerInterface
    {
        /...
    }
}
```

```yml
services:
    # ...
    Pitch\Liform\ResolverInterface:
        class: App\MyLiformResolver
```

## Extension

An __Extension__ in __Liform__ manipulates a `TransformResult`.
It should add data structures that are shared across multiple Form Types.

```php
use Pitch\Liform\Extension\ExtensionInterface;
use Pitch\Liform\TransformResult;
use Symfony\Component\Form\FormView;

class MyLiformExtension implements ExtensionInterface
{
    public function apply(TransformResult $transformResult, FormView $formView): void
    {
        // ...
    }
}
```

```yml
services:
    App\MyLiformExtension:
        tags:
            - { name: liform.extension }
```
