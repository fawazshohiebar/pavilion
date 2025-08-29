<template>
    <div class="colored-text-fieldtype">
        <!-- Rich Text Display -->
        <div class="relative input-group overflow-hidden">
            <!-- Styled text overlay -->
            <div 
                ref="styledOverlay"
                class="input-text absolute inset-0 pointer-events-none whitespace-nowrap overflow-hidden z-10 text-transparent"
                v-html="styledText"
            ></div>
            
            <!-- Hidden text input for actual input -->
            <input 
                ref="textInput"
                type="text"
                :value="cleanText" 
                @input="updateText"
                @select="handleTextSelection"
                @mouseup="handleTextSelection"
                @keyup="handleTextSelection"
                @scroll="syncScroll"
                :placeholder="config.placeholder || 'Enter text...'"
                class="input-text transition-all relative z-20 bg-transparent text-transparent caret-black"
                style="color: transparent !important;"
            />
        </div>

        <!-- Color Selection Toolbar -->
        <div v-if="hasSelection" class="flex items-center justify-between mt-1 px-1 py-1 bg-blue-50 border border-blue-200 rounded text-2xs">
            <div class="flex items-center gap-1">
                <span class="text-gray-500">Color:</span>
                <button
                    v-for="color in availableColors"
                    :key="color.key"
                    @click="applyColor(color.key)"
                    :class="[
                        'w-6 h-6 rounded-full border border-gray-300 hover:border-gray-400 transition-all duration-200',
                        getColorBackgroundClass(color.key)
                    ]"
                    type="button"
                    :title="`Apply ${color.label} color`"
                >
                </button>
                <button
                    @click="removeColor"
                    class="w-6 h-6 rounded-full bg-white border border-red-300 hover:border-red-400 transition-all duration-200 flex items-center justify-center text-red-600 font-bold text-xs"
                    type="button"
                    title="Remove color formatting"
                >
                    ×
                </button>
            </div>
        </div>

        <!-- WYSIWYG-style Controls -->
        <div v-if="!hasSelection" class="flex flex-wrap lg:flex-nowrap items-center gap-2 mt-1 text-2xs">
            <!-- Size Selection -->
            <div class="flex items-center gap-1 bg-white rounded px-2 py-1 border border-gray-200">
                <span class="sr-only">Size:</span>
                <div class="flex gap-1">
                    <button 
                        v-for="size in sizes"
                        :key="size.key"
                        @click="updateSize(size.key)"
                        :class="[
                            'px-2 py-1 rounded transition-colors text-xs font-medium',
                            currentSize === size.key
                                ? 'bg-blue-500 text-white'
                                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                        ]"
                        type="button"
                        :title="`Size: ${size.label}`"
                    >
                        {{ size.label }}
                    </button>
                </div>
            </div>

            <!-- Tag Selection -->
            <div class="flex items-center gap-1 bg-white rounded px-2 py-1 border border-gray-200">
                <span class="sr-only">Tag:</span>
                <div class="flex gap-1">
                    <button 
                        v-for="tag in tags"
                        :key="tag.key"
                        @click="updateTag(tag.key)"
                        :class="[
                            'px-2 py-1 rounded transition-colors text-xs font-medium',
                            currentTag === tag.key
                                ? 'bg-blue-500 text-white'
                                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                        ]"
                        type="button"
                        :title="`Tag: ${tag.label}`"
                    >
                        {{ tag.label }}
                    </button>
                </div>
            </div>

            <!-- Color Selection -->
            <div class="flex items-center gap-1 bg-white rounded px-2 py-1 border border-gray-200">
                <span class="sr-only">Color:</span>
                <div class="flex gap-1">
                    <button 
                        v-for="color in availableColors"
                        :key="color.key"
                        @click="updateColor(color.key)"
                        :class="[
                            'w-6 h-6 rounded-full border-2 transition-all duration-200',
                            currentColor === color.key
                                ? 'border-blue-500 ring-2 ring-blue-200'
                                : 'border-gray-300 hover:border-gray-400'
                        ]"
                        type="button"
                        :title="`Select ${color.label} color`"
                    >
                        <span 
                            class="w-full h-full rounded-full block"
                            :class="getColorBackgroundClass(color.key)"
                        ></span>
                    </button>
                </div>
            </div>

            <!-- Uppercase Toggle -->
            <div class="flex items-center gap-1 bg-white rounded px-2 py-1 border border-gray-200">
                <span class="sr-only">Style:</span>
                <button 
                    @click="toggleUppercase"
                    :title="`Uppercase: ${currentUppercase ? 'On' : 'Off'}`"
                    :class="[
                        'flex items-center gap-1 px-2 py-1 rounded transition-colors text-xs font-medium',
                        currentUppercase 
                            ? 'bg-blue-500 text-white' 
                            : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                    ]"
                    type="button"
                >
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                    </svg>
                    {{ currentUppercase ? 'ABC' : 'abc' }}
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    mixins: [Fieldtype],

    data() {
        return {
            hasSelection: false,
            selectedText: '',
            selectionStart: 0,
            selectionEnd: 0,
            sizes: [
                { key: 'xsmall', label: 'XS' },
                { key: 'small', label: 'S' },
                { key: 'large', label: 'L' },
                { key: 'xlarge', label: 'XL' }
            ],
            tags: [
                { key: 'p', label: 'P' },
                { key: 'h1', label: 'H1' },
                { key: 'h2', label: 'H2' },
                { key: 'h3', label: 'H3' },
                { key: 'h4', label: 'H4' }
            ]
        }
    },

    computed: {
        replicatorPreview() {
            return this.value?.text ?? '' + 
                (this.value?.text && this.value?.color ? ` (${this.value.color})` : '') +
                (this.value?.text && this.value?.size ? ` {${this.value.size}}` : '');
        },
        textValue() {
            return this.value?.text || '';
        },

        currentSize() {
            return this.value?.size || 'small';
        },

        currentColor() {
            return this.value?.color || 'black';
        },

        currentTag() {
            return this.value?.tag || 'p';
        },

        currentUppercase() {
            return this.value?.uppercase || false;
        },

        currentSizeLabel() {
            const option = this.sizes.find(s => s.key === this.currentSize);
            return option ? option.label : 'S';
        },

        currentTagLabel() {
            const option = this.tags.find(t => t.key === this.currentTag);
            return option ? option.label : 'P';
        },

        currentColorLabel() {
            const option = this.availableColors && this.availableColors.find(c => c.key === this.currentColor);
            return option ? option.label : 'Black';
        },

        currentColorBgClass() {
            const colorBgClasses = {
                'black': 'bg-black',
                'white': 'bg-white',
                'primary': 'bg-primary-600',
                'secondary': 'bg-secondary-600',
            };
            return colorBgClasses[this.currentColor] || 'bg-black';
        },

        availableColors() {
            // Get colors from the fieldtype meta data passed from PHP preload
            if (this.meta && this.meta.color_options) {
                // Handle if color_options is an array
                if (Array.isArray(this.meta.color_options)) {
                    return this.meta.color_options.map(option => ({
                        key: option.key || option.value,
                        label: option.label || this.formatColorLabel(option.key || option.value)
                    }));
                }
                
                // Handle if color_options is an object (key-value pairs)
                if (typeof this.meta.color_options === 'object') {
                    return Object.entries(this.meta.color_options).map(([key, label]) => ({
                        key: key,
                        label: label
                    }));
                }
            }
            
            // Return empty array if no meta is available
            return [];
        },

        styledText() {
            if (!this.textValue) return '';
            
            // Convert {color:text} to styled spans for display (no size/style effects)
            let styledText = this.textValue.replace(/\{(\w+):([^}]+)\}/g, (match, color, text) => {
                const colorClass = this.getColorClass(color);
                return `<span class="${colorClass} font-medium">${text}</span>`;
            });
            
            return styledText;
        },

        // Clean text without color syntax for editing
        cleanText() {
            if (!this.textValue) return '';
            
            // Remove color syntax, keep only the text content
            return this.textValue.replace(/\{(\w+):([^}]+)\}/g, (match, color, text) => text);
        }
    },

    methods: {
        updateText(event) {
            // Get the clean text input
            const newCleanText = event.target.value;
            
            // Update with the clean text
            this.updateValue({ text: newCleanText });
            this.$nextTick(() => {
                this.syncScroll();
            });
        },

        syncScroll() {
            const input = this.$refs.textInput;
            const overlay = this.$refs.styledOverlay;
            if (input && overlay) {
                overlay.scrollLeft = input.scrollLeft;
            }
        },

        updateSize(size) {
            this.updateValue({ size });
        },

        updateColor(color) {
            this.updateValue({ color });
        },

        updateTag(tag) {
            this.updateValue({ tag });
        },

        cycleSize() {
            const currentIndex = this.sizes.findIndex(s => s.key === this.currentSize);
            const nextIndex = (currentIndex + 1) % this.sizes.length;
            this.updateSize(this.sizes[nextIndex].key);
        },

        cycleTag() {
            const currentIndex = this.tags.findIndex(t => t.key === this.currentTag);
            const nextIndex = (currentIndex + 1) % this.tags.length;
            this.updateTag(this.tags[nextIndex].key);
        },

        cycleColor() {
            if (!this.availableColors || this.availableColors.length === 0) return;
            
            const currentIndex = this.availableColors.findIndex(c => c.key === this.currentColor);
            const nextIndex = (currentIndex + 1) % this.availableColors.length;
            this.updateColor(this.availableColors[nextIndex].key);
        },

        toggleUppercase() {
            this.updateValue({ uppercase: !this.currentUppercase });
        },

        updateValue(updates) {
            const newValue = {
                text: this.textValue,
                size: this.currentSize,
                color: this.currentColor,
                tag: this.currentTag,
                uppercase: this.currentUppercase,
                ...updates
            };
            this.update(newValue);
        },

        handleTextSelection() {
            const textarea = this.$refs.textInput;
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            
            if (start !== end) {
                this.hasSelection = true;
                this.selectedText = textarea.value.substring(start, end);
                this.selectionStart = start;
                this.selectionEnd = end;
            } else {
                this.clearSelection();
            }
        },

        clearSelection() {
            this.hasSelection = false;
            this.selectedText = '';
            this.selectionStart = 0;
            this.selectionEnd = 0;
        },

        applyColor(color) {
            
            if (!this.hasSelection) return;

            const textarea = this.$refs.textInput;
            const cleanTextValue = this.cleanText;
            const before = cleanTextValue.substring(0, this.selectionStart);
            const after = cleanTextValue.substring(this.selectionEnd);
            const selectedText = cleanTextValue.substring(this.selectionStart, this.selectionEnd);
            
            // Create the new text with color syntax
            const coloredText = `{${color}:${selectedText}}`;
            const newValue = before + coloredText + after;
            
            this.updateValue({ text: newValue });
            this.clearSelection();

            // Refocus and set cursor position
            this.$nextTick(() => {
                const newCursorPos = this.selectionStart + selectedText.length;
                textarea.focus();
                textarea.setSelectionRange(newCursorPos, newCursorPos);
            });
        },

        removeColor() {
            if (!this.hasSelection) return;

            const textarea = this.$refs.textInput;
            const selectedText = this.selectedText;
            
            // Since we're working with clean text, we need to find the corresponding
            // section in the stored text and remove color formatting
            // For now, we'll just replace the selected text as plain text
            const cleanTextValue = this.cleanText;
            const before = cleanTextValue.substring(0, this.selectionStart);
            const after = cleanTextValue.substring(this.selectionEnd);
            const newValue = before + selectedText + after;
            
            this.updateValue({ text: newValue });
            this.clearSelection();
            
            this.$nextTick(() => {
                const newCursorPos = this.selectionStart + selectedText.length;
                textarea.focus();
                textarea.setSelectionRange(newCursorPos, newCursorPos);
            });
        },

        getColorBackgroundClass(color) {
            
            const backgroundClasses = {
                black: 'bg-black',
                white: 'bg-white',
                primary: 'bg-primary-600',
                secondary: 'bg-secondary-600'
            };
            return backgroundClasses[color] || 'bg-black';
        },

        getColorClass(color) {
            const colorClasses = {
                black: 'text-black',
                white: 'text-white',
                primary: 'text-primary-600',
                secondary: 'text-secondary-600'
            };
            return colorClasses[color] || 'text-black';
        },

        formatColorLabel(colorKey) {
            // Convert kebab-case or snake_case to Title Case
            return colorKey
                .split(/[-_]/)
                .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                .join(' ');
        }
    },

    mounted() {
        // Sync scroll on mount
        this.$nextTick(() => {
            this.syncScroll();
        });
    }
}
</script>

<style scoped>
.colored-text-fieldtype {
    position: relative;
}

/* Ensure the overlay matches the input exactly */
.colored-text-fieldtype input {
    font-family: inherit;
    line-height: 1.5;
    caret-color: #000;
}

.colored-text-fieldtype .absolute {
    font-family: inherit;
    line-height: 1.5;
    word-wrap: break-word;
    overflow-wrap: break-word;
}

/* Make sure the caret is visible */
.colored-text-fieldtype input:focus {
    caret-color: #000 !important;
}
</style>
