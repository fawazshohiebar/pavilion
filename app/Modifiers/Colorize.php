<?php

namespace App\Modifiers;

use Statamic\Modifiers\Modifier;

class Colorize extends Modifier
{
    /**
     * Transform {color:text} syntax into HTML spans with color classes
     *
     * @param mixed $value
     * @param array $params
     * @param array $context
     * @return string
     */
    public function index($value, $params, $context)
    {
        if (!is_string($value)) {
            return $value;
        }

        // Convert {color:text} to <span class="colored-text-color">text</span>
        return preg_replace(
            '/\{(\w+):([^}]+)\}/',
            '<span class="colored-text-$1">$2</span>',
            $value
        );
    }
}
