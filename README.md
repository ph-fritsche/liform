[![codecov](https://codecov.io/gh/ph-fritsche/liform/branch/master/graph/badge.svg)](https://codecov.io/gh/ph-fritsche/liform)

# ![Liform](https://ph-fritsche.github.io/liform/assets/liform.png)

Library for transforming [Symfony Form Views](https://symfony.com/doc/current/components/form.html) into JSON.

It is developed to be used with [liform-react-final](https://www.npmjs.com/package/liform-react-final), but can be used with any form rendering supporting [JSON schema](http://json-schema.org/).

## Installation

Install per [Composer](https://getcomposer.org/) from [Packagist](https://packagist.org/packages/pitch/liform).
```
composer require pitch/liform
```

If you execute this inside a Symfony application with [Symfony Flex](https://symfony.com/doc/current/setup/flex.html),
`LiformInterface` will be available per [Dependency Injection](https://symfony.com/doc/current/service_container.html#injecting-services-config-into-a-service) right away.

## Basic usage

```php
$form = \Symfony\Component\Form\Forms::createFormFactory()->create();
$form->add('foo', \Symfony\Component\Form\Extension\Core\Type\TextType::class);
$form->add('bar', \Symfony\Component\Form\Extension\Core\Type\NumberType::class);

/* ... handle the request ... */

$resolver = new \Pitch\Liform\Resolver();
$resolver->setTransformer('text', new \Pitch\Liform\Transformer\StringTransformer());
$resolver->setTransformer('number', new \Pitch\Liform\Transformer\NumberTransformer());
/* ... */

$liform = new \Pitch\Liform\Liform($resolver);
$liform->addExtension(new \Pitch\Liform\Extension\ValueExtension());
$liform->addExtension(new \Pitch\Liform\Extension\LabelExtension());
/* ... */

return $liform->transform($form->createView());
```

### Inside a Symfony application

```php
namespace App\Controller;

/* use statements */

class MyFormController extends Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
   protected LiformInterface $liform;

   /* Let the service container inject the service */
   public function __construct(LiformInterface $liform)
   {
      $this->liform = $liform;
   }

   public function __invoke(Request $request)
   {
      $form = $this->createForm(MyFormType::class);
      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
         /* ... do something ... */
      } else {
         return new Response($this->render('my_form.html.twig', [
            'liform' => $this->liform->transform($form->createView()),
         ]), $form->isSubmitted() ? 400 : 200);
      }
   }
}
```

### The result

The `TransformResult` is an object that when passed through `json_encode()` will produce something like:
```js
{
   "schema": {
      "title": "form",
      "type": "object",
      "properties": {
         "foo": {
            "title": "foo",
            "type": "string"
         },
         "bar": {
            "title": "bar",
            "type": "number",
         }
      },
      "required": [
         "foo",
         "bar"
      ]
   },
   "meta": {
      "errors": {
         "foo": ["This is required."]
      }
   },
   "values": {
      "bar": 42
   }
}
```

## Acknowledgements

This library is based on [Limenius/Liform](https://github.com/Limenius/Liform).
Its technique for transforming forms using resolvers and reducers is inspired by [Symfony Console Form](https://github.com/matthiasnoback/symfony-console-form).
