<?php
namespace Pitch\Liform;

use ReflectionClass;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\CoreExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;

class SymfonyFormType extends FormType
{
    public static $lastConfig;

    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ) {
        parent::buildForm($builder, $options);

        $config = $this->getConfig(
            $this->getBuiltInTypes(),
            $this->provideTypeParams(),
            $this->provideDefaultOptions(),
        );
    
        foreach ($config as $subName => $c) {
            $builder->add($subName, $c['class'], $c['options']);
            if (\array_key_exists('data', $c)) {
                $builder->get($subName)->setData($c['data']);
            }
        }

        $this::$lastConfig = $config;
    }

    protected function getBuiltInTypes(): array
    {
        $coreExtension = new ReflectionClass(CoreExtension::class);
        $typeDir = dirname($coreExtension->getFileName()) . '/Type';
        $typeNamespace = $coreExtension->getNamespaceName() . '\\Type\\';

        $types = [];
        foreach ((new Finder())->files()->name("*.php")->in($typeDir) as $typeFile) {
            $name = basename($typeFile, '.php');
            $className = $typeNamespace . $name;
            $classRefl = new ReflectionClass($className);

            if (!$classRefl->isAbstract() && $classRefl->implementsInterface(FormTypeInterface::class)) {
                $types[$name] = $className;
            }
        }

        return $types;
    }

    protected function getConfig(
        array $types,
        array $params,
        array $defaultOptions
    ): array {
        $config = [];

        foreach ($types as $typeName => $typeClass) {
            $typeParamsCollection = $params[$typeName] ?? [null];
            foreach ($typeParamsCollection as $i => $typeParams) {
                $subName = $typeName;
                if (\count($typeParamsCollection) > 1 || !\is_numeric($i)) {
                    $subName .= '_' . $i;
                }

                $resolver = new OptionsResolver();
                $resolveType = $typeClass;
                while ($resolveType) {
                    $typeObject = new $resolveType();
                    $typeObject->configureOptions($resolver);
                    $resolveType = $typeObject->getParent();
                }
                $definedOptions = $resolver->getDefinedOptions();
                $defaultOptions = array_filter(
                    $defaultOptions,
                    fn($k) => in_array($k, $definedOptions),
                    ARRAY_FILTER_USE_KEY
                );

                $config[$subName] = [
                    'class' => $typeClass,
                    'options' => array_merge($defaultOptions, $typeParams['options'] ?? []),
                ];

                if (\array_key_exists('data', $typeParams ?? [])) {
                    $config[$subName]['data'] = $typeParams['data'];
                }
            }
        }

        return $config;
    }

    protected function provideDefaultOptions(): array
    {
        return [
            'error_bubbling' => false,
            'attr' => [
                'placeholder' => 'PlaceholderText',
            ],
            'widget' => 'single_text',
        ];
    }

    protected function provideTypeParams(): array
    {
        $params = [];

        // ChoiceTransformer
        $params['ChoiceType']['default'] = [
            'data' => 'bar',
            'options' => [
                'choices' => ['Foo' => 'foo', 'Bar' => 'bar', 'Baz' => 'baz'],
            ],
        ];

        $params['ChoiceType']['multiple'] = [
            'data' => ['bar', 'baz'],
            'options' => [
                'choices' => ['Foo' => 'foo', 'Bar' => 'bar', 'Baz' => 'baz'],
                'multiple' => true,
            ],
        ];

        $params['ChoiceType']['expanded'] = [
            'data' => 'bar',
            'options' => [
                'choices' => ['Foo' => 'foo', 'Bar' => 'bar', 'Baz' => 'baz'],
                'expanded' => true,
            ],
        ];

        $params['ChoiceType']['expandedMultiple'] = [
            'data' => ['bar'],
            'options' => [
                'choices' => ['Foo' => 'foo', 'Bar' => 'bar', 'Baz' => 'baz'],
                'expanded' => true,
                'multiple' => true,
            ],
        ];

        // CollectionTransformer
        $params['CollectionType']['allowAdd'] = [
            'data' => ['foo', 'bar'],
            'options' => [
                'allow_add' => true,
            ],
        ];
        $params['CollectionType']['allowDelete'] = [
            'data' => ['foo', 'bar'],
            'options' => [
                'allow_delete' => true,
            ],
        ];

        return $params;
    }
}
