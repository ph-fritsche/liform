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

use PHPUnit\Framework\TestCase;
use Pitch\Liform\Exception\TransformerException;
use Pitch\Liform\Transformer\TransformerInterface;
use Symfony\Component\Form\FormView;

/**
 * @author Philipp Fritsche <ph.fritsche@gmail.com>
 */
class ResolverTest extends TestCase
{
    public function testResolve()
    {
        $view = new FormView();
        $view->vars = ['block_prefixes' => ['foo', 'bar', 'baz']];
        $transformer = $this->createMock(TransformerInterface::class);

        $resolver = new Resolver();
        $resolver->setTransformer('foo', clone $transformer);
        $resolver->setTransformer('bar', $transformer);

        $resolvedTransfomer = $resolver->resolve($view);

        $this->assertSame($transformer, $resolvedTransfomer);
    }

    public function testException()
    {
        $view = new FormView();

        $resolver = new Resolver();

        $this->expectException(TransformerException::class);

        $resolver->resolve($view);
    }
}
