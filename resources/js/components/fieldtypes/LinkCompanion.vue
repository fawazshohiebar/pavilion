<template>
    <div class="space-y-2">
        <!-- First Row: Icon, Position, New Tab -->
        <div class="flex items-center justify-between w-full">
            <!-- Icon Selector -->
            <div class="flex items-center gap-2">
                <label class="text-xs font-medium text-gray-700 whitespace-nowrap">Icon:</label>
                <select 
                    v-model="linkData.icon" 
                    @change="updateValue"
                    class="select-input text-xs h-7 w-36"
                >
                    <option value="">🔗 Default</option>
                    <option v-for="icon in iconOptions" :key="icon.value" :value="icon.value">
                        {{ icon.label }}
                    </option>
                </select>
            </div>

            <!-- Icon Position -->
            <div class="flex items-center gap-1">
                <label class="text-xs font-medium text-gray-700 whitespace-nowrap">Pos:</label>
                <div class="flex gap-1">
                    <button 
                        v-for="position in iconPositionOptions" 
                        :key="position.value" 
                        @click="setIconPosition(position.value)" 
                        :class="[
                            'px-2 py-1 rounded text-xs font-medium transition-colors',
                            linkData.icon_position === position.value
                                ? 'bg-blue-500 text-white'
                                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                        ]" 
                        type="button"
                        :title="position.label"
                    >
                        {{ position.short }}
                    </button>
                </div>
            </div>

            <!-- New Tab Toggle -->
            <div class="flex items-center gap-2">
                <label class="text-xs font-medium text-gray-700 whitespace-nowrap">New Tab:</label>
                <toggle-input
                    v-model="linkData.new_tab"
                    @input="updateValue"
                />
            </div>
        </div>

        <!-- Second Row: Style, Colors, Size -->
        <div class="flex items-center justify-between w-full">
            <!-- Style Controls -->
            <div class="flex items-center gap-1">
                <label class="text-xs font-medium text-gray-700 whitespace-nowrap">Style:</label>
                <div class="flex gap-1">
                    <button 
                        v-for="style in styleOptions" 
                        :key="style.value" 
                        @click="setStyle(style.value)" 
                        :class="[
                            'px-2 py-1 rounded text-xs font-medium transition-colors',
                            linkData.style === style.value
                                ? 'bg-blue-500 text-white'
                                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                        ]" 
                        type="button" 
                        :title="style.label"
                    >
                        {{ style.short }}
                    </button>
                </div>
            </div>

            <!-- Color Controls -->
            <div class="flex items-center gap-1">
                <label class="text-xs font-medium text-gray-700 whitespace-nowrap">Color:</label>
                <div class="flex gap-1">
                    <button 
                        v-for="color in colorOptions" 
                        :key="color.value" 
                        @click="setColor(color.value)" 
                        :class="[
                            'w-6 h-6 rounded border-2 transition-all',
                            linkData.color === color.value
                                ? 'border-blue'
                                : 'border-gray-300 hover:border-gray-400',
                            color.bgClass
                        ]" 
                        type="button" 
                        :title="color.label"
                    >
                    </button>
                </div>
            </div>

            <!-- Size Controls -->
            <div class="flex items-center gap-1">
                <label class="text-xs font-medium text-gray-700 whitespace-nowrap">Size:</label>
                <div class="flex gap-1">
                    <button 
                        v-for="size in sizeOptions" 
                        :key="size.value" 
                        @click="setSize(size.value)" 
                        :class="[
                            'px-2 py-1 rounded text-xs font-medium transition-colors',
                            linkData.size === size.value
                                ? 'bg-blue-500 text-white'
                                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                        ]" 
                        type="button"
                    >
                        {{ size.short }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    mixins: [Fieldtype],

    data() {
        return {
            linkData: {
                style: 'filled',
                color: 'black',
                size: 'medium',
                new_tab: false,
                icon: null,
                icon_position: 'start'
            }
        }
    },

    computed: {
        iconOptions() {
            // Get icon options from the fieldtype meta data passed from PHP preload
            if (this.meta && this.meta.icon_options) {
                return this.meta.icon_options.map(option => ({
                    value: option.key || option.value,
                    label: option.label || this.formatIconLabel(option.key || option.value)
                }));
            }
            
            // Return empty array if no meta is available
            return [];
        },

        replicatorPreview() {
            return (this.linkData.icon || 'Link') + 
                (this.linkData.style ? ` (${this.linkData.style})` : '') +
                (this.linkData.color ? ` [${this.linkData.color}]` : '') +
                (this.linkData.size ? ` {${this.linkData.size}}` : '')
        },
        styleOptions() {
            return [
                { value: 'filled', label: 'Filled', short: 'F' },
                { value: 'outline', label: 'Outline', short: 'O' },
                { value: 'text', label: 'Text Only', short: 'T' },
            ];
        },

        colorOptions() {
            // Use the color options from PHP config, with fallback to default options
            if (this.meta && this.meta.color_options) {
                const colorOptions = this.meta.color_options;
                return Object.entries(colorOptions).map(([value, label]) => ({
                    value,
                    label,
                    bgClass: this.getColorClass(value)
                }));
            }
            return [];
        },

        sizeOptions() {
            return [
                { value: 'small', label: 'Small', short: 'S' },
                { value: 'medium', label: 'Medium', short: 'M' },
                { value: 'large', label: 'Large', short: 'L' }
            ];
        },

        iconPositionOptions() {
            return [
                { value: 'start', label: 'Start', short: 'S' },
                { value: 'end', label: 'End', short: 'E' }
            ];
        }
    },

    watch: {
        value: {
            immediate: true,
            handler(newVal) {
                if (newVal && typeof newVal === 'object') {
                    this.linkData = {
                        style: newVal.style || this.config.default_style || 'filled',
                        color: newVal.color || this.config.default_color || 'black',
                        size: newVal.size || this.config.default_size || 'medium',
                        new_tab: newVal.new_tab !== undefined ? newVal.new_tab : (this.config.default_new_tab || false),
                        icon: newVal.icon || null,
                        icon_position: newVal.icon_position || this.config.default_icon_position || 'start'
                    };
                } else {
                    this.linkData = {
                        style: this.config.default_style || 'filled',
                        color: this.config.default_color || 'black',
                        size: this.config.default_size || 'medium',
                        new_tab: this.config.default_new_tab || false,
                        icon: null,
                        icon_position: this.config.default_icon_position || 'start'
                    };
                }
            }
        }
    },

    methods: {
        updateValue() {
            this.$emit('input', { ...this.linkData });
        },

        setStyle(style) {
            this.linkData.style = style;
            this.updateValue();
        },

        setColor(color) {
            this.linkData.color = color;
            this.updateValue();
        },

        setSize(size) {
            this.linkData.size = size;
            this.updateValue();
        },

        setIconPosition(position) {
            this.linkData.icon_position = position;
            this.updateValue();
        },

        getColorClass(colorValue) {
            // Map color values to CSS background classes
            const colorMap = {
                'black': 'bg-black',
                'white': 'bg-white',
                'primary': 'bg-primary-600',
                'secondary': 'bg-gray-600',
                'blue': 'bg-blue-500',
                'red': 'bg-red-500',
                'green': 'bg-green-500',
                'yellow': 'bg-yellow-400'
            };
            
            return colorMap[colorValue] || 'bg-gray-500';
        },

        formatIconLabel(iconKey) {
            // Convert kebab-case to Title Case
            return iconKey
                .split('-')
                .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                .join(' ');
        }
    }
}
</script>
