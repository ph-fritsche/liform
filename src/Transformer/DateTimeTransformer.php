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
    /**
     * @see https://www.php.net/manual/de/function.date.php
     */
    const PHPDATEPATTERNS = [
        'd' => '[012]\d|3[01]',
        'D' => '[a-zA-Z]{3}',
        'j' => '[012]?\d|3[01]',
        'l' => '[a-zA-Z]+',
        'N' => '[1-7]',
        'S' => 'st|nd|rd|th',
        'w' => '[0-6]',
        'z' => '[012]?\d?\d|3[0-5]\d|36[0-5]',
        
        'W' => '[0-4]?\d|5[0-3]',
        
        'F' => '[a-zA-Z]+',
        'm' => '0[1-9]|1[12]',
        'M' => '[a-zA-Z]{3}',
        'n' => '0?[1-9]|1[12]',
        't' => '2[89]|3[01]',

        'L' => '[01]',
        'o' => '\d{4}',
        'Y' => '\d{4}',
        'y' => '\d{2}|\d{4}',

        'a' => '[ap]m',
        'A' => '[AP]M',
        'B' => '\d{3}',
        'g' => '0?[1-9]|1[012]',
        'G' => '[01]?\d|2[0-3]',
        'h' => '(0[1-9]|1[012]',
        'H' => '[01]\d|2[0-3]',
        'i' => '[0-5]\d',
        's' => '[0-5]\d',
        'u' => '\d{6}',
        'v' => '\d{3}',

        'e' => '[a-zA-Z]+(?:\\/[a-zA-Z]+)?',
        'I' => '[01]',
        'O' => '\+(0[1-9]|1[012])([0-5]?\d)',
        'P' => '\+(0[1-9]|1[012]):([0-5]?\d)',
        'T' => '[a-zA-Z]{3,4}',
        'Z' => '-?\d+',

        // Y-m-dTH:i:sP
        'c' => '\d{4}-0[1-9]|1[12]-[012]\d|3[01]'
            . 'T[01]\d|2[0-3]:[0-5]\d:[0-5]\d'
            . '\+(0[1-9]|1[012]):([0-5]?\d)',

        // D, j M Y H:i:s O
        'r' => '[a-zA-Z]{3}, [012]?\d|3[01] [a-zA-Z]{3} \d{4} '
            . '[01]\d|2[0-3]:[0-5]\d:[0-5]\d'
            . ' \+(0[1-9]|1[012])([0-5]?\d)',

        'U' => '\d+',
    ];

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
            $result->schema->pattern = $this->getFormatRegexp('Y-\WW');
        } elseif (\in_array('dateinterval', $view->vars['block_prefixes'])) {
            $result->schema->pattern = '^(\\+|-)?P' .
                '(-?\\d+Y)?(-?\\d+M)?(-?\\d+D)?(-?\\d+W)?' .
                '(T(-?\\d+H)?(-?\\d+M)?(-?\\d+S)?)?' .
                '$';
        } elseif (\in_array('date', $view->vars['block_prefixes'])) {
            // vars['type'] is 'date' if a html5 date input is expected to be rendered
            if (isset($view->vars['type'])) {
                $result->schema->format = $view->vars['type'];
            } else {
                $result->schema->pattern = $this->getFormatRegexp('Y-m-d');
            }
        } elseif (\in_array('time', $view->vars['block_prefixes'])) {
            $result->schema->pattern = $this->getFormatRegexp('H(?::i(?::s)?)?(?:P)?');
        } elseif (\in_array('datetime', $view->vars['block_prefixes'])) {
            $result->schema->pattern = $this->getFormatRegexp('y-m-d\TH(?::i(?::s)?)?(?:P)?');
        }

        return $result;
    }

    /**
     * Translate PHP date formats to RegExp patterns
     */
    protected function getFormatRegexp(
        string $dateFormat
    ): string {
        $result = '';

        for ($i = 0, $escaped = false; $i < strlen($dateFormat); $i++) {
            if ($escaped) {
                $result .= $dateFormat[$i];
                $escaped = false;
            } elseif ($dateFormat[$i] === '\\') {
                $escaped = true;
            } else {
                $result .= isset(self::PHPDATEPATTERNS[ $dateFormat[$i] ])
                    ? '(' . self::PHPDATEPATTERNS[ $dateFormat[$i] ] . ')'
                    : $dateFormat[$i];
            }
        }

        return '^' . $result . '$';
    }
}
