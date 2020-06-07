<?php
namespace Pitch\Liform;

use PHPUnit\Framework\TestCase;
use Pitch\Liform\Extension\ExtensionInterface;
use Pitch\Liform\Transformer\TransformerInterface;
use Symfony\Component\Form\FormView;

class LiformTest extends TestCase
{
    public function testTransformWithExtensions()
    {
        $view = new FormView();
        $result = new TransformResult();

        $transformer = $this->createMock(TransformerInterface::class);
        $transformer->expects($this->once())->method('transform')
            ->with($view)
            ->willReturn($result);

        $resolver = $this->createMock(ResolverInterface::class);
        $resolver->expects($this->once())->method('resolve')
            ->with($view)
            ->willReturn($transformer);

        $liform = new Liform($resolver);

        $extension = $this->createMock(ExtensionInterface::class);
        $extension->expects($this->once())->method('apply')
            ->with($result, $view);
        $liform->addExtension($extension);

        $this->assertSame($result, $liform->transform($view));
    }
}
