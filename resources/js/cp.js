/**
 * When extending the control panel, be sure to uncomment the necessary code for your build process:
 * https://statamic.dev/extending/control-panel
 */

/** Example Fieldtype

import ExampleFieldtype from './components/fieldtypes/ExampleFieldtype.vue';

Statamic.booting(() => {
    Statamic.$components.register('example-fieldtype', ExampleFieldtype);
});

*/

// LinkCompanion Fieldtype
import LinkCompanion from './components/fieldtypes/LinkCompanion.vue';
import ColoredText from './components/fieldtypes/ColoredText.vue';

Statamic.booting(() => {
    Statamic.$components.register('link_companion-fieldtype', LinkCompanion);
    Statamic.$components.register('colored_text-fieldtype', ColoredText);
});
