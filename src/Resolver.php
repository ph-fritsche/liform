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

use Pitch\Liform\Exception\TransformerException;
use Pitch\Liform\Transformer\TransformerInterface;
use Symfony\Component\Form\FormView;

/**
 * @author Nacho Martín <nacho@limenius.com>
 * @author Philipp Fritsche <ph.fritsche@gmail.com>
 */
class Resolver implements ResolverInterface
{
    /**
     * @var TransformerInterface[]
     */
    private $transformers = [];

    public function setTransformer(
        string $blockPrefix,
        TransformerInterface $transformer
    ): void {
        $this->transformers[$blockPrefix] = $transformer;
    }

    public function resolve(
        FormView $view
    ): TransformerInterface {
        $blockPrefixes = $view->vars['block_prefixes'] ?? [];

        foreach (array_reverse($blockPrefixes) as $prefix) {
            if (isset($this->transformers[$prefix])) {
                return $this->transformers[$prefix];
            }
        }

        throw new TransformerException(sprintf(
            'Could not find a transformer for any of these block prefixes (%s)',
            implode(', ', $blockPrefixes)
        ));
    }
}
