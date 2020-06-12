<?php
namespace Pitch\Liform;

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Form\Forms;

$formFactory = Forms::createFormFactory();
$form = $formFactory->create(SymfonyFormType::class);

$liformFactory = new LiformFactory();
$liform = $liformFactory->createLiform();

$result = $liform->transform($form->createView());

$buildDir = __DIR__ . '/docs/build';
if (!file_exists($buildDir)) {
    mkdir($buildDir, 0777, true);
}
file_put_contents($buildDir . '/SymfonyFormType.json', \json_encode($result, JSON_PRETTY_PRINT));
file_put_contents($buildDir . '/SymfonyFormType.config.json', \json_encode(SymfonyFormType::$lastConfig, JSON_PRETTY_PRINT));
