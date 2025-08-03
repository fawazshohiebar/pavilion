<?php

namespace App\Fieldtypes;

use Statamic\Fieldtypes\Text;

class ColoredText extends Text
{
    protected static $handle = 'colored_text';

    /**
     * The fieldtype icon.
     *
     * @var string
     */
    protected $icon = 'text';

    /**
     * The fieldtype categories.
     *
     * @var array
     */
    protected $categories = ['text'];

    /**
     * Configuration field items for the fieldtype.
     *
     * @return array
     */
    protected function configFieldItems(): array
    {
        $config = parent::configFieldItems();

        // Add the collapsed option to the Appearance & Behavior section
        $config[1]['fields']['color'] = [
            'display' => 'Default Color',
            'instructions' => 'Choose the default color of the text field',
            'type' => 'select',
            'default' => 'primary',
            'options' => [
                'black' => 'Black',
                'white' => 'White',
                'primary' => 'Primary',
                'secondary' => 'Secondary',
            ],
            'width' => 50
        ];
        $config[1]['fields']['size'] = [
            'display' => 'Default Size',
            'instructions' => 'Choose the default size of the text field',
            'type' => 'select',
            'default' => 'small',
            'options' => [
                'small' => 'Small',
                'large' => 'Large',
                'xlarge' => 'Extra Large',
            ],
            'width' => 33
        ];
        $config[1]['fields']['tag'] = [
            'display' => 'Default Tag',
            'instructions' => 'Choose the default HTML tag for the text',
            'type' => 'select',
            'default' => 'p',
            'options' => [
                'p' => 'Paragraph (p)',
                'h1' => 'Heading 1 (h1)',
                'h2' => 'Heading 2 (h2)',
                'h3' => 'Heading 3 (h3)',
                'h4' => 'Heading 4 (h4)',
            ],
            'width' => 25
        ];
        $config[1]['fields']['uppercase'] = [
            'display' => 'Default Uppercase',
            'instructions' => 'Should text be uppercase by default',
            'type' => 'toggle',
            'default' => false,
            'width' => 25
        ];

        // Add highlight options to the existing structure
        $config[1]['fields']['available_colors'] = [
            'display' => 'Available Colors',
            'instructions' => 'Choose which colors content editors can apply to selected text',
            'type' => 'checkboxes',
            'default' => ['primary', 'secondary', 'black', 'white'],
            'options' => [
                'black' => 'Black',
                'white' => 'White', 
                'primary' => 'Primary Color',
                'secondary' => 'Secondary Color',
            ],
            'width' => 100
        ];

        return $config;
    }

    protected function getColorOptions(): array
    {
        return [
            'primary' => 'Primary',
            'secondary' => 'Secondary',
            'black' => 'Black',
            'white' => 'White',
            // Add more colors as needed
        ];
    }

    public function preload()
    {
        return [
            'color_options' => $this->getColorOptions(),
        ];
    }

     /**
     * Pre-process the data before it gets sent to the publish page.
     *
     * @param mixed $data
     * @return array|mixed
     */
    public function preProcess($data)
    {
        if (is_string($data)) {
            // Legacy support: convert string to object
            return [
                'text' => $data,
                'size' => $this->config('size', 'small'),
                'color' => $this->config('color', 'black'),
                'tag' => $this->config('tag', 'p'),
                'uppercase' => $this->config('uppercase', false),
            ];
        }

        return $data;
    }

    /**
     * Augment the data for frontend display.
     *
     * @param mixed $value
     * @return mixed
     */
    public function augment($value)
    {
        // Return the full object so templates can access text, size, and color
        if (is_array($value)) {
            return $value;
        }
        $augmented = [];

        // Legacy: if it's just a string, convert it
        if (is_string($value)) {
            $augmented = [
                'text' => $value,
                'size' => $this->config('size', 'small'),
                'color' => $this->config('color', 'black'),
                'tag' => $this->config('tag', 'p'),
                'uppercase' => $this->config('uppercase', false),
            ];
        }   
        
        if (is_array($augmented) && isset($augmented['text'])) {
            $augmented['processed_text'] = $this->processHighlights($augmented['text']);
        }

        return $augmented;
    }

    /**
     * Process highlights in the text based on color syntax.
     *
     * @param string $text
     * @return string
     */
    private function processHighlights($text)
    {
        // Convert {color:text} to <span class="text-color-600">text</span>
        return preg_replace_callback('/\{(\w+):([^}]+)\}/', function($matches) {
            $color = $matches[1];
            $text = $matches[2];
            $colorClass = $this->getColorClass($color);
            return '<span class="' . $colorClass . '">' . $text . '</span>';
        }, $text);
    }

    /**
     * Get the CSS class for the specified color.
     *
     * @param string $color
     * @return string
     */
    private function getColorClass($color = null)
    {
        if (!$color) {
            $color = $this->config('highlight_color', 'supporting');
        }
        
        return match($color) {
            'black' => 'text-black',
            'white' => 'text-white',
            'gray' => 'text-gray-600',
            'supporting' => 'text-supporting-600',
            default => 'text-black',
        };
    }
}
