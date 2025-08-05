<template>
    <div class="style-config-fieldtype">
        <!-- Compact Style Controls -->
        <div class="flex flex-wrap lg:flex-nowrap items-center gap-2 text-2xs">
            <!-- Padding Selection -->
            <div class="flex items-center gap-1 bg-white rounded px-2 py-1 border border-gray-200">
                <span class="text-gray-500 text-xs">Padding:</span>
                <div class="flex gap-1">
                    <button 
                        v-for="(label, key) in paddingOptions"
                        :key="key"
                        @click="updatePadding(key)"
                        :class="[
                            'px-2 py-1 rounded transition-colors text-xs font-medium',
                            currentPadding === key
                                ? 'bg-blue-500 text-white'
                                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                        ]"
                        type="button"
                        :title="`Padding: ${label}`"
                    >
                        {{ getPaddingAbbreviation(key) }}
                    </button>
                </div>
            </div>

            <!-- Color Selection -->
            <div class="flex items-center gap-1 bg-white rounded px-2 py-1 border border-gray-200">
                <span class="text-gray-500 text-xs">Color:</span>
                <div class="flex gap-1">
                    <button 
                        v-for="(label, key) in colorOptions"
                        :key="key"
                        @click="updateColor(key)"
                        :class="[
                            'w-6 h-6 rounded-full border-2 transition-all duration-200',
                            currentColor === key
                                ? 'border-blue-400 ring-2 ring-blue-200'
                                : 'border-gray-300 hover:border-gray-400'
                        ]"
                        type="button"
                        :title="`Color: ${label}`"
                    >
                        <span 
                            class="w-full h-full rounded-full block"
                            :class="getColorBackgroundClass(key)"
                        ></span>
                    </button>
                </div>
            </div>

            <!-- Size Selection -->
            <div class="flex items-center gap-1 bg-white rounded px-2 py-1 border border-gray-200">
                <span class="text-gray-500 text-xs">Size:</span>
                <div class="flex gap-1">
                    <button 
                        v-for="(label, key) in sizeOptions"
                        :key="key"
                        @click="updateSize(key)"
                        :class="[
                            'px-2 py-1 rounded transition-colors text-xs font-medium',
                            currentSize === key
                                ? 'bg-blue-500 text-white'
                                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                        ]"
                        type="button"
                        :title="`Size: ${label}`"
                    >
                        {{ getSizeAbbreviation(key) }}
                    </button>
                </div>
            </div>

            <!-- Variant Selection -->
            <div class="flex items-center gap-1 bg-white rounded px-2 py-1 border border-gray-200">
                <span class="text-gray-500 text-xs">Variant:</span>
                <div class="flex gap-1">
                    <button 
                        v-for="(label, key) in variantOptions"
                        :key="key"
                        @click="updateVariant(key)"
                        :class="[
                            'px-2 py-1 rounded transition-colors text-xs font-medium',
                            currentVariant === key
                                ? 'bg-blue-500 text-white'
                                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                        ]"
                        type="button"
                        :title="`Variant: ${label}`"
                    >
                        {{ getVariantAbbreviation(label) }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    mixins: [Fieldtype],

    computed: {
        replicatorPreview() {
            return `${this.currentPadding}|${this.currentColor}|${this.currentSize}|${this.currentVariant}`;
        },

        currentPadding() {
            return this.value?.padding || 'small';
        },

        currentColor() {
            return this.value?.color || 'transparent';
        },

        currentSize() {
            return this.value?.size || 'normal';
        },

        currentVariant() {
            return this.value?.variant || 'style1';
        },

        paddingOptions() {
            return this.meta?.padding_options || {
                'none': 'None',
                'small': 'Small',
                'large': 'Large',
                'xlarge': 'Extra Large'
            };
        },

        colorOptions() {
            return this.meta?.color_options || {
                'black': 'Black',
                'white': 'White',
                'transparent': 'Transparent',
                'primary': 'Primary',
                'secondary': 'Secondary',
                'gray': 'Gray',
                'lite-primary': 'Lite Primary',
                'lite-secondary': 'Lite Secondary'
            };
        },

        sizeOptions() {
            return this.meta?.size_options || {
                'compact': 'Compact',
                'normal': 'Normal'
            };
        },

        variantOptions() {
            return this.meta?.variant_options;
        }
    },

    methods: {
        updatePadding(padding) {
            this.updateValue({ padding });
        },

        updateColor(color) {
            this.updateValue({ color });
        },

        updateSize(size) {
            this.updateValue({ size });
        },

        updateVariant(variant) {
            this.updateValue({ variant });
        },

        updateValue(updates) {
            const newValue = {
                padding: this.currentPadding,
                color: this.currentColor,
                size: this.currentSize,
                variant: this.currentVariant,
                ...updates
            };
            this.update(newValue);
        },

        getPaddingAbbreviation(key) {
            const abbreviations = {
                'none': '0',
                'small': 'S',
                'large': 'L',
                'xlarge': 'XL'
            };
            return abbreviations[key] || key.charAt(0).toUpperCase();
        },

        getSizeAbbreviation(key) {
            const abbreviations = {
                'compact': 'C',
                'normal': 'N'
            };
            return abbreviations[key] || key.charAt(0).toUpperCase();
        },

        getVariantAbbreviation(label) {
            // Extract number or use first 2 chars
            const match = label.match(/\d+/);
            if (match) {
                return `V${match[0]}`;
            }
            return label.substring(0, 2).toUpperCase();
        },

        getColorBackgroundClass(color) {
            const backgroundClasses = {
                'black': 'bg-black',
                'white': 'bg-white border border-gray-300',
                'transparent': 'bg-transparent border border-gray-400 border-dashed',
                'primary': 'bg-primary-600',
                'secondary': 'bg-secondary-600',
                'gray': 'bg-gray-200',
                'lite-primary': 'bg-primary-50',
                'lite-secondary': 'bg-secondary-50'
            };
            return backgroundClasses[color] || 'bg-gray-400';
        }
    }
}
</script>

<style scoped>
.style-config-fieldtype {
    position: relative;
}

/* Visual indicators for color swatches */
.style-config-fieldtype button[title*="Transparent"] span {
    background-image: repeating-linear-gradient(
        45deg,
        transparent,
        transparent 2px,
        #e5e7eb 2px,
        #e5e7eb 4px
    );
}
</style>
