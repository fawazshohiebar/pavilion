<?php

namespace App\Fieldtypes;

use Statamic\Fields\Fieldtype;

class StyleConfig extends Fieldtype
{
    protected static $handle = 'style_config';

    /**
     * The fieldtype icon.
     *
     * @var string
     */
    protected $icon = 'settings';

    /**
     * The fieldtype categories.
     *
     * @var array
     */
    protected $categories = ['special'];

    /**
     * Configuration field items for the fieldtype.
     *
     * @return array
     */
    protected function configFieldItems(): array
    {
        return [
            [
                'display' => 'General',
                'fields' => [
                    'available_padding_sizes' => [
                        'display' => 'Available Padding Sizes',
                        'instructions' => 'Choose which padding sizes content editors can select',
                        'type' => 'checkboxes',
                        'default' => ['none', 'small', 'large', 'xlarge'],
                        'options' => [
                            'none' => 'None',
                            'small' => 'Small',
                            'large' => 'Large',
                            'xlarge' => 'Extra Large',
                        ],
                        'width' => 50
                    ],
                    'default_padding' => [
                        'display' => 'Default Padding Size',
                        'instructions' => 'Choose the default padding size',
                        'type' => 'select',
                        'default' => 'small',
                        'options' => [
                            'none' => 'None',
                            'small' => 'Small',
                            'large' => 'Large',
                            'xlarge' => 'Extra Large',
                        ],
                        'width' => 50
                    ],
                    'available_colors' => [
                        'display' => 'Available Colors',
                        'instructions' => 'Choose which colors content editors can select',
                        'type' => 'checkboxes',
                        'default' => ['black', 'white', 'transparent', 'primary', 'secondary', 'gray', 'lite-primary', 'lite-secondary'],
                        'options' => [
                            'black' => 'Black',
                            'white' => 'White',
                            'transparent' => 'Transparent',
                            'primary' => 'Primary',
                            'secondary' => 'Secondary',
                            'gray' => 'Gray',
                            'lite-primary' => 'Lite Primary',
                            'lite-secondary' => 'Lite Secondary',
                        ],
                        'width' => 50
                    ],
                    'default_color' => [
                        'display' => 'Default Color',
                        'instructions' => 'Choose the default color',
                        'type' => 'select',
                        'default' => 'transparent',
                        'options' => [
                            'black' => 'Black',
                            'white' => 'White',
                            'transparent' => 'Transparent',
                            'primary' => 'Primary',
                            'secondary' => 'Secondary',
                            'gray' => 'Gray',
                            'lite-primary' => 'Lite Primary',
                            'lite-secondary' => 'Lite Secondary',
                        ],
                        'width' => 50
                    ],
                    'available_sizes' => [
                        'display' => 'Available Sizes',
                        'instructions' => 'Choose which sizes content editors can select',
                        'type' => 'checkboxes',
                        'default' => ['compact', 'normal'],
                        'options' => [
                            'compact' => 'Compact',
                            'normal' => 'Normal',
                        ],
                        'width' => 50
                    ],
                    'default_size' => [
                        'display' => 'Default Size',
                        'instructions' => 'Choose the default size',
                        'type' => 'select',
                        'default' => 'normal',
                        'options' => [
                            'compact' => 'Compact',
                            'normal' => 'Normal',
                        ],
                        'width' => 50
                    ],
                ]
            ],
            [
                'display' => 'Variants',
                'fields' => [
                    'variant_labels' => [
                        'display' => 'Variant Labels',
                        'instructions' => 'Customize the labels for each style variant (one per line: variant_key|Custom Label)',
                        'type' => 'textarea',
                        'default' => "style1|Style 1\nstyle2|Style 2\nstyle3|Style 3",
                        'width' => 50
                    ],
                    'default_variant' => [
                        'display' => 'Default Variant',
                        'instructions' => 'Enter the default variant key (e.g., style1, style2, etc.)',
                        'type' => 'text',
                        'default' => 'style1',
                        'width' => 50
                    ],
                ]
            ]
        ];
    }

    /**
     * Get available options based on configuration.
     */
    public function preload()
    {
        return [
            'padding_options' => $this->getPaddingOptions(),
            'color_options' => $this->getColorOptions(),
            'size_options' => $this->getSizeOptions(),
            'variant_options' => $this->getVariantOptions(),
        ];
    }

    /**
     * Get padding size options.
     */
    protected function getPaddingOptions(): array
    {
        $available = $this->config('available_padding_sizes', ['none', 'small', 'large', 'xlarge']);
        $options = [
            'none' => 'None',
            'small' => 'Small',
            'large' => 'Large',
            'xlarge' => 'Extra Large',
        ];

        return collect($available)->mapWithKeys(function ($key) use ($options) {
            return [$key => $options[$key] ?? ucfirst($key)];
        })->toArray();
    }

    /**
     * Get color options.
     */
    protected function getColorOptions(): array
    {
        $available = $this->config('available_colors', ['black', 'white', 'transparent', 'primary', 'secondary', 'gray', 'lite-primary', 'lite-secondary']);
        $options = [
            'black' => 'Black',
            'white' => 'White',
            'transparent' => 'Transparent',
            'primary' => 'Primary',
            'secondary' => 'Secondary',
            'gray' => 'Gray',
            'lite-primary' => 'Lite Primary',
            'lite-secondary' => 'Lite Secondary',
        ];

        return collect($available)->mapWithKeys(function ($key) use ($options) {
            return [$key => $options[$key] ?? ucfirst($key)];
        })->toArray();
    }

    /**
     * Get size options.
     */
    protected function getSizeOptions(): array
    {
        $available = $this->config('available_sizes', ['compact', 'normal']);
        $options = [
            'compact' => 'Compact',
            'normal' => 'Normal',
        ];

        return collect($available)->mapWithKeys(function ($key) use ($options) {
            return [$key => $options[$key] ?? ucfirst($key)];
        })->toArray();
    }

    /**
     * Get variant options.
     */
    protected function getVariantOptions(): array
    {
        $variantLabels = $this->config('variant_labels', "style1|Style 1\nstyle2|Style 2\nstyle3|Style 3");
        $variants = [];

        foreach (explode("\n", $variantLabels) as $line) {
            $line = trim($line);
            if (empty($line)) continue;

            $parts = explode('|', $line, 2);
            $key = trim($parts[0]);
            $label = isset($parts[1]) ? trim($parts[1]) : ucfirst($key);
            
            if ($key) {
                $variants[$key] = $label;
            }
        }

        return $variants ?: ['style1' => 'Style 1', 'style2' => 'Style 2', 'style3' => 'Style 3'];
    }

    /**
     * Pre-process the data before it gets sent to the publish page.
     *
     * @param mixed $data
     * @return array|mixed
     */
    public function preProcess($data)
    {
        if (!is_array($data)) {
            // Initialize with defaults
            return [
                'padding' => $this->config('default_padding', 'small'),
                'color' => $this->config('default_color', 'transparent'),
                'size' => $this->config('default_size', 'normal'),
                'variant' => $this->config('default_variant', 'style1'),
            ];
        }

        // Ensure all required keys exist
        return array_merge([
            'padding' => $this->config('default_padding', 'small'),
            'color' => $this->config('default_color', 'transparent'),
            'size' => $this->config('default_size', 'normal'),
            'variant' => $this->config('default_variant', 'style1'),
        ], $data);
    }

    /**
     * Augment the data for frontend display.
     *
     * @param mixed $value
     * @return mixed
     */
    public function augment($value)
    {
        if (!is_array($value)) {
            $value = $this->preProcess($value);
        }

        // Add computed CSS classes
        $value['css_classes'] = $this->generateCssClasses($value);
        
        return $value;
    }

    /**
     * Generate CSS classes based on the configuration.
     */
    protected function generateCssClasses($config): string
    {
        $classes = [];

        // Padding classes
        if (!empty($config['padding']) && $config['padding'] !== 'none') {
            $classes[] = 'padding-' . $config['padding'];
        }

        // Color classes
        if (!empty($config['color']) && $config['color'] !== 'transparent') {
            $classes[] = 'color-' . $config['color'];
        }

        // Size classes
        if (!empty($config['size']) && $config['size'] !== 'normal') {
            $classes[] = 'size-' . $config['size'];
        }

        // Variant classes
        if (!empty($config['variant'])) {
            $classes[] = 'variant-' . $config['variant'];
        }

        return implode(' ', $classes);
    }
}
