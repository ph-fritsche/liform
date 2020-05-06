<?php

/*
 * This file is part of the Limenius\Liform package.
 *
 * (c) Limenius <https://github.com/Limenius/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pitch\Liform;

use Pitch\Liform\Transformer\ExtensionInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @author Nacho Martín <nacho@limenius.com>
 */
class Liform implements LiformInterface
{
    /**
     * @var ResolverInterface
     */
    private $resolver;

    /**
     * @var ExtensionInterface[]
     */
    private $extensions = [];

    /**
     * @param ResolverInterface $resolver
     */
    public function __construct(ResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * {@inheritdoc}
     */
    public function transform(FormInterface $form)
    {
        $transformerData = $this->resolver->resolve($form);

        return $transformerData['transformer']->transform($form, $this->extensions, $transformerData['widget']);
    }

    /**
     * {@inheritdoc}
     */
    public function addExtension(ExtensionInterface $extension)
    {
        $this->extensions[] = $extension;

        return $this;
    }
}
