<?php

namespace App\Fieldtypes;

use Statamic\Fields\Fieldtype;
use Statamic\Fieldtypes\Text;
use Statamic\Facades\Fieldset;

class LinkCompanion extends Text
{
    protected function getColorOptions(): array
    {
        return [
            'black' => 'Black',
            'white' => 'White',
            'primary' => 'Primary',
            'secondary' => 'Secondary',
            // Add more colors as needed
        ];
    }
    /**
     * Get icon options from the basics fieldset.
     *
     * @return array
     */
    protected function getIconOptions(): array
    {
        try {
            $basicsFieldset = Fieldset::find('basics');
            
            if ($basicsFieldset) {
                $fields = $basicsFieldset->contents()['fields'] ?? [];
                
                $iconField = collect($fields)->firstWhere('handle', 'icon');
                
                if ($iconField && isset($iconField['field']['options'])) {
                    return collect($iconField['field']['options'])->map(function ($option) {
                        return [
                            'key' => $option['key'],
                            'value' => $option['key'],
                            'label' => $this->formatIconLabel($option['key'])
                        ];
                    })->toArray();
                }
            }
        } catch (\Exception $e) {
            // Log error if needed
        }
        
        return [];
    }

    /**
     * Format icon key to readable label.
     *
     * @param string $iconKey
     * @return string
     */
    protected function formatIconLabel($iconKey): string
    {
        return collect(explode('-', $iconKey))
            ->map(fn($word) => ucfirst($word))
            ->join(' ');
    }

    /**
     * Pass additional data to the Vue component.
     *
     * @return array
     */
    public function preload()
    {
        return [
            'icon_options' => $this->getIconOptions(),
            'color_options' => $this->getColorOptions(),
        ];
    }

    /**
     * The blank/default value.
     *
     * @return array
     */
    public function defaultValue()
    {
        return [
            'style' => $this->config('style', 'filled'),
            'color' => $this->config('color', 'black'),
            'size' => $this->config('size', 'medium'),
            'new_tab' => $this->config('new_tab', false),
            'icon' => $this->config('icon', null),
            'icon_position' => $this->config('icon_position', 'start'),
        ];
    }

    /**
     * Pre-process the data before it gets saved to the front-matter.
     *
     * @param mixed $data
     * @return array
     */
    public function preProcess($data)
    {
        if (!is_array($data)) {
            return $this->defaultValue();
        }

        return array_merge($this->defaultValue(), $data);
    }

    /**
     * Process the data after it gets loaded from the front-matter.
     *
     * @param mixed $data
     * @return array
     */
    public function process($data)
    {
        if (!is_array($data)) {
            return $this->defaultValue();
        }

        return array_merge($this->defaultValue(), $data);
    }

    /**
     * The fieldtype's configuration fields.
     *
     * @return array
     */
    protected function configFieldItems(): array
    {
        $config = parent::configFieldItems();
        $config[1]['fields']['style'] = [
            'display' => 'Default Style',
            'instructions' => 'Choose the default button style',
            'type' => 'select',
            'default' => 'filled',
            'options' => [
                'filled' => 'Filled',
                'outline' => 'Outline',
                'text' => 'Text Only',
                'animated_link' => 'Animated Link',
            ],
            'width' => 33
        ];
        $config[1]['fields']['size']  = [
            'display' => 'Default Size',
            'instructions' => 'Choose the default button size',
            'type' => 'select',
            'default' => 'medium',
            'options' => [
                'small' => 'Small',
                'medium' => 'Medium',
                'large' => 'Large',
            ],
            'width' => 34
        ];
        $config[1]['fields']['color'] = [
            'display' => 'Default Color',
            'instructions' => 'Choose the default color scheme',
            'type' => 'select',
            'default' => 'black',
            'options' => [
                'black' => 'Black',
                'white' => 'White',
                'primary' => 'Primary',
                'secondary' => 'Secondary',
            ],
            'width' => 33
        ];
        $config[1]['fields']['new_tab'] = [
            'display' => 'Default Open in New Tab',
            'instructions' => 'Should links open in new tab by default',
            'type' => 'toggle',
            'default' => false,
            'width' => 50
        ];
        $config[1]['fields']['icon_position'] = [
            'display' => 'Default Icon Position',
            'instructions' => 'Choose the default position of the icon',
            'type' => 'select',
            'default' => 'start',
            'options' => [
                'start' => 'Start',
                'end' => 'End',
            ],
            'width' => 50
        ];

        $config[1]['fields']['icon'] = [
            'display' => 'Default Icon',
            'instructions' => 'Choose the default icon for the link',
            'type' => 'select',
            'default' => null,
            'width' => 100
        ];

        return $config;
    }
}
