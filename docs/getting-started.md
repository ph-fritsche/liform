---
nav_order: 0
---

# Getting started

## Installation

Install per [Composer](https://getcomposer.org/) from [Packagist](https://packagist.org/packages/pitch/liform).
```
composer require pitch/liform
```

If you execute this inside a Symfony application with [Symfony Flex](https://symfony.com/doc/current/setup/flex.html),
`LiformInterface` will be available per [Dependency Injection](https://symfony.com/doc/current/service_container.html#injecting-services-config-into-a-service) right away.

## Usage

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
        }

        /* transform to json_encode'able object */
        $liformResult = $this->liform->transform($form->createView());

        /* determine how to respond */
        if ($this->isJsonPreferred($request)) {
            return new JsonResponse($liformResult, $form->isSubmitted() ? 400 : 200);
        } else {
            return new Response(
                $this->render('my_form.html.twig', ['liform' => $liformResult]),
                $form->isSubmitted() ? 400 : 200
            );
        }
    }
}
```
