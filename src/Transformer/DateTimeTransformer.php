<?php

/*
 * This file is part of the Pitch\Liform package.
 *
 * (c) Philipp Fritsche <ph.fritsche@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pitch\Liform\Transformer;

use Pitch\Liform\TransformResult;
use Symfony\Component\Form\FormView;

class DateTimeTransformer extends CompoundTransformer implements TransformerInterface
{
    public function transform(
        FormView $view
    ): TransformResult {
        // if the view is to be rendered as an object with multiple fields
        if ($view->vars['compound'] ?? false) {
            return parent::transform($view);
        }

        $result = new TransformResult();

        $result->schema->type = 'string';

        if (\in_array('week', $view->vars['block_prefixes'])) {

            $result->schema->pattern = '^\d\d\d\d-W([0-4]?\d|5[0-3])$';

        } elseif (\in_array('dateinterval', $view->vars['block_prefixes'])) {

            $result->schema->pattern = '^(\\+|-)?P(-?\\d+Y)?(-?\\d+M)?(-?\\d+D)?(-?\\d+W)?(T(-?\\d+H)?(-?\\d+M)?(-?\\d+S)?)?$';

        } elseif (\in_array('date', $view->vars['block_prefixes'])) {

            // vars['type'] is 'date' if a html5 date input is expected to be rendered
            // vars['format'] should contain the expected format for view data
            $result->schema->format = $view->vars['type'] ?? $view->vars['format'];

        } elseif (\in_array('time', $view->vars['block_prefixes'])) {

            // vars['type'] is 'time' if a html5 time input is expected to be rendered
            if (isset($view->vars['type'])) {
                $result->schema->format = $view->vars['type'];
            } else {
                $result->schema->format = 'H';
                $result->schema->format .= ($view->vars['with_minutes'] ?? false) ? ':i' : '';
                $result->schema->format .= ($view->vars['with_seconds'] ?? false) ? ':s' : '';
            }

        } else {

            // vars['type'] is 'datetime-local' if a html5 time input is expected to be rendered
            $result->schema->format = isset($view->vars['type']) ? 'date-time': $view->vars['format'] ?? 'y-m-dTH:i:s';

        }

        return $result;
    }
}
