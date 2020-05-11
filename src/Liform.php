<?php

/*
 * Original file is part of the Limenius\Liform package.
 *
 * (c) Limenius <https://github.com/Limenius/>
 *
 * This file is part of the Pitch\Liform package.
 *
 * (c) Philipp Fritsche <ph.fritsche@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pitch\Liform;

use Pitch\Liform\Extension\ExtensionInterface;
use Symfony\Component\Form\FormView;

/**
 * @author Nacho Martín <nacho@limenius.com>
 * @author Philipp Fritsche <ph.fritsche@gmail.com>
 */
class Liform implements LiformInterface
{
    protected ResolverInterface $resolver;

    /**
     * @var ExtensionInterface[]
     */
    protected $extensions = [];

    public function __construct(
        ResolverInterface $resolver
    ) {
        $this->resolver = $resolver;
    }

    public function transform(
        FormView $view
    ): TransformResult {
        $transformer = $this->resolver->resolve($view);

        $result = $transformer->transform($view);

        foreach ($this->extensions as $extension) {
            $extension->apply($result, $view);
        }

        return $result;
    }

    public function addExtension(
        ExtensionInterface $extension
    ) {
        $this->extensions[] = $extension;
    }
}
