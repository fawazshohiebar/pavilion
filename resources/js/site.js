import Alpine from 'alpinejs'
 
import Precognition from 'laravel-precognition-alpine';
import intersect from '@alpinejs/intersect'
import collapse from '@alpinejs/collapse'
import '@tailwindplus/elements';
 
window.Alpine = Alpine;

Alpine.plugin(Precognition);
Alpine.plugin(intersect);
Alpine.plugin(collapse);

Alpine.start();